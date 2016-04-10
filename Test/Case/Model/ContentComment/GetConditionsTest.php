<?php
/**
 * ContentComment::getConditions()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentComment::getConditions()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\ContentComment
 */
class ContentCommentGetConditionsTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'content_comments';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'ContentComment';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getConditions';

/**
 * getConditions()のテスト - ログイン
 *
 * @return void
 */
	public function testGetConditions() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$contentKeys = 'content_1';
		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Plugin']['key'] = 'plugin_1';
		Current::$current['User']['id'] = 1;	// ログイン

		//テスト実施
		$result = $this->$model->$methodName($contentKeys);

		//チェック
		$this->assertArrayHasKey('OR', $result);
		//debug($result);
	}

/**
 * getConditions()のテスト - ログインしていない
 *
 * @return void
 */
	public function testGetConditionsNotLogin() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$contentKeys = 'content_1';
		$blockKey = 'block_1';
		$pluginKey = 'plugin_1';
		Current::$current['Block']['key'] = $blockKey;
		Current::$current['Plugin']['key'] = $pluginKey;

		//テスト実施
		$result = $this->$model->$methodName($contentKeys);

		//チェック
		$this->assertEquals($contentKeys, $result['content_key']);
		$this->assertEquals($blockKey, $result['block_key']);
		$this->assertEquals($pluginKey, $result['plugin_key']);
		$this->assertEquals(WorkflowComponent::STATUS_PUBLISHED, $result['ContentComment.status']);
		//debug($result);
	}

/**
 * getConditions()のテスト - 公開許可あり
 *
 * @return void
 */
	public function testGetConditionsPublishable() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$contentKeys = 'content_1';
		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Plugin']['key'] = 'plugin_1';
		Current::$current['User']['id'] = 1;	// ログイン
		Current::$current['Permission']['content_comment_publishable']['value'] = 1;

		//テスト実施
		$result = $this->$model->$methodName($contentKeys);

		//チェック
		$this->assertArrayNotHasKey('OR', $result);
		$this->assertArrayNotHasKey('ContentComment.status', $result);
		//debug($result);
	}
}
