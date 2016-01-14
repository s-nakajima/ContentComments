<?php
/**
 * コンテンツコメント件数 Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentComment', 'ContentComments.Model');

/**
 * Summary for ContentComment Behavior
 */
class ContentCommentCountBehavior extends ModelBehavior {

/**
 * @var array 設定
 */
	public $settings = array();

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
		// コンテンツコメント件数をセット
		$contentKeys = array();
		foreach ($results as $key => &$target) {
			$contentKeys[] = $target[$model->alias]['key'];
			$target['ContentCommentCnt']['content_key'] = $target[$model->alias]['key'];
		}

		$ContentComment = ClassRegistry::init('ContentComments.ContentComment');

		// バーチャルフィールドを追加  http://book.cakephp.org/2.0/ja/models/virtual-fields.html#sql
		$ContentComment->virtualFields['cnt'] = 0;

		$contentCommentCnts = $ContentComment->find('all', array(
			'recursive' => -1,
			'fields' => array('content_key', 'count(content_key) as ContentComment__cnt'),	// Model__エイリアスにする
			'conditions' => array(
				'plugin_key' => Current::read('Plugin.key'),
				'status' => ContentComment::STATUS_PUBLISHED,
			),
			'group' => array('block_key', 'plugin_key', 'content_key'),
		));

		foreach ($results as $key => &$target) {
			$target['ContentCommentCnt']['cnt'] = 0;
			foreach ($contentCommentCnts as $contentCommentCnt) {
				if ($target['ContentCommentCnt']['content_key'] == $contentCommentCnt['ContentComment']['content_key']) {
					$target['ContentCommentCnt']['cnt'] = $contentCommentCnt['ContentComment']['cnt'];
					break;
				}
			}
		}

		return $results;
	}
}