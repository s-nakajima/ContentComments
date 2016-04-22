<?php
/**
 * コンテンツコメント Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * Summary for ContentComment Behavior
 */
class ContentCommentBehavior extends ModelBehavior {

/**
 * @var bool 削除済みか
 */
	private $__isDeleted = null;

/**
 * setup
 *
 * @param Model $model モデル
 * @param array $settings 設定値
 * @return void
 * @link http://book.cakephp.org/2.0/ja/models/behaviors.html#ModelBehavior::setup
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias] = $settings;
		$this->__isDeleted = false;

		$model->ContentComment = ClassRegistry::init('ContentComments.ContentComment', true);
	}

/**
 * afterFind
 * コンテンツコメント件数をセット
 *
 * @param Model $model モデル
 * @param mixed $results Find結果
 * @param bool $primary primary
 * @return array $results
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function afterFind(Model $model, $results, $primary = false) {
		if (empty($results) || ! isset($results[0][$model->alias]['key'])) {
			return $results;
		}
		if ($model->recursive == -1) {
			return $results;
		}

		// コンテンツコメント件数をセット
		$contentKeys = array();
		foreach ($results as &$result) {
			$result['ContentCommentCnt'] = array(
				'cnt' => 0
			);
			$contentKey = $result[$model->alias]['key'];
			$contentKeys[] = $contentKey;
		}

		/* @see ContentComment::getConditions() */
		$conditions = $model->ContentComment->getConditions($contentKeys);

		$results = $this->__applyCnt($model, $results, $conditions, 'cnt');

		// 公開権限なし
		if (! Current::permission('content_comment_publishable')) {
			return $results;
		}

		// --- 未承認件数の取得
		// 未承認のみ
		$conditions['ContentComment.status'] = WorkflowComponent::STATUS_APPROVED;
		$results = $this->__applyCnt($model, $results, $conditions, 'approval_cnt');

		return $results;
	}

/**
 * 件数をFind結果に付ける
 *
 * @param Model $model モデル
 * @param mixed $results Find結果
 * @param array $conditions 条件
 * @param string $virtualFieldName 件数のバーチャルフィールド名
 * @return array Find結果
 * @throws InternalErrorException
 */
	private function __applyCnt(Model $model, $results, $conditions, $virtualFieldName) {
		// バーチャルフィールドを追加
		/* @link http://book.cakephp.org/2.0/ja/models/virtual-fields.html#sql */
		$model->ContentComment->virtualFields[$virtualFieldName] = 0;

		$contentCommentCnts = $model->ContentComment->find('all', array(
			'recursive' => -1,
			// Model__エイリアスにする
			'fields' => array('content_key',
				'count(content_key) as ContentComment__' . $virtualFieldName),
			'conditions' => $conditions,
			'group' => array('content_key'),
			'callbacks' => false,
		));

		foreach ($results as &$result) {
			$contentKey = $result[$model->alias]['key'];
			foreach ($contentCommentCnts as $contentCommentCnt) {
				if ($contentKey == $contentCommentCnt['ContentComment']['content_key']) {
					$result['ContentCommentCnt'][$virtualFieldName] =
						$contentCommentCnt['ContentComment'][$virtualFieldName];
					break;
				}
			}
		}

		return $results;
	}

/**
 * beforeDelete
 * コンテンツが削除されたら、書いてあったコメントも削除
 *
 * @param Model $model Model using this behavior
 * @param bool $cascade If true records that depend on this record will also be deleted
 * @return mixed False if the operation should abort. Any other result will continue.
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/ja/models/behaviors.html#ModelBehavior::beforedelete
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforedelete
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function beforeDelete(Model $model, $cascade = true) {
		// 多言語のコンテンツを key を使って、Model::deleteAll() で削除した場合を想定
		// 削除済みなら、もう処理をしない
		if ($this->__isDeleted) {
			return;
		}

		// コンテンツ取得
		$content = $model->find('first', array(
			'conditions' => array($model->alias . '.id' => $model->id)
		));

		// コンテンツコメント 削除
		$conditions = array('ContentComment.content_key' => $content[$model->alias]['key']);
		if (! $model->ContentComment->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$this->__isDeleted = true;
		return true;
	}
}