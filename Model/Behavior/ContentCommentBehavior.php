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
				'table' => '( SELECT c.content_key, COUNT(*) as cnt' .
					' FROM content_comments c' .
					' GROUP BY c.block_key, c.plugin_key, c.content_key )',
				'alias' => 'ContentCommentCnt',
				'conditions' => $Model->alias . '.key = ContentCommentCnt.content_key',
			);
		}

		return $query;
	}
}