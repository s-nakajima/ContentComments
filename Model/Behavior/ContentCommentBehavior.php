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

/**
 * Summary for ContentComment Behavior
 */
class ContentCommentBehavior extends ModelBehavior {

/**
 * @var array 設定
 */
	public $settings = array();

/**
 * setup
 *
 * @param Model $Model モデル
 * @param array $settings 設定値
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		$this->settings[$Model->alias] = $settings;
	}

/**
 * 検索時のフィールドにコンテンツコメント数があったらJOINする
 *
 * @param Model $Model タグ使用モデル
 * @param array $query find条件
 * @return array タグ検索条件を加えたfind条件
 */
	public function beforeFind(Model $Model, $query) {
		// フィールドにコンテンツコメント数があったらJOINする
		if ($query['fields'] !== null && strpos( $query['fields'], 'ContentCommentCnt.cnt')) {
			$query['joins'][] = array(
				'type' => 'LEFT',
				'table' => '( SELECT content_key, COUNT(*) as cnt' .
					' FROM content_comments' .
					' WHERE status = ' . ContentComment::STATUS_PUBLISHED .
					' GROUP BY block_key, plugin_key, content_key )',
				'alias' => 'ContentCommentCnt',
				'conditions' => $Model->alias . '.key = ContentCommentCnt.content_key',
			);
		}

		return $query;
	}

/**
 * コンテンツコメント数、NULLなら0をセット
 *
 * @param Model $Model モデル
 * @param mixed $results Find結果
 * @param bool $primary primary
 * @return array $results
 */
	public function afterFind(Model $Model, $results, $primary = false) {
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