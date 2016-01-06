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
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias] = $settings;
	}

/**
 * 検索時のフィールドにコンテンツコメント数があったらJOINする
 *
 * @param Model $model タグ使用モデル
 * @param array $query find条件
 * @return array タグ検索条件を加えたfind条件
 */
	public function beforeFind(Model $model, $query) {
		// フィールドにコンテンツコメント数があったらJOINする
		if ((gettype($query['fields']) == 'string' && strpos($query['fields'], 'ContentCommentCnt.cnt')) ||
			(gettype($query['fields']) == 'array' && array_search('ContentCommentCnt.cnt', $query['fields']))) {
			$query['joins'][] = array(
				'type' => 'LEFT',
				'table' => '( SELECT content_key, COUNT(*) as cnt' .
					' FROM content_comments' .
					' WHERE status = ' . ContentComment::STATUS_PUBLISHED .
					' GROUP BY block_key, plugin_key, content_key )',
				'alias' => 'ContentCommentCnt',
				'conditions' => $model->alias . '.key = ContentCommentCnt.content_key',
			);
		}

		return $query;
	}

/**
 * コンテンツコメント数、NULLなら0をセット
 *
 * @param Model $model モデル
 * @param mixed $results Find結果
 * @param bool $primary primary
 * @return array $results
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function afterFind(Model $model, $results, $primary = false) {
		if (isset($results[0]['ContentCommentCnt'])) {
			foreach ($results as $key => $target) {
				// NULLなら0をセット
				if (!isset($target['ContentCommentCnt']['cnt'])) {
					$results[$key]['ContentCommentCnt']['cnt'] = 0;
				}
			}
		}
		return $results;
	}
}