<?php
/**
 * ContentCommentBehavior::find()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestContentCommentBehaviorFindModelFixture', 'ContentComments.Test/Fixture');

/**
 * ContentCommentBehavior::find()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\Behavior\ContentCommentBehavior
 */
class ContentCommentBehaviorFindTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.test_content_comment_behavior_find_model',
		'plugin.content_comments.content_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'content_comments';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'ContentComments', 'TestContentComments');
		$this->TestModel = ClassRegistry::init('TestContentComments.TestContentCommentBehaviorFindModel');
	}

/**
 * テストPermissionの取得
 *
 * @param string $value 1:許可する
 * @return array
 */
	private function __getPermission($value) {
		$permission = array(
			'content_comment_publishable' => array('value' => $value),
			//'content_comment_editable' => array('value' => $value),
			//'content_comment_creatable' => array('value' => $value),
		);

		return $permission;
	}

/**
 * find()のテスト - コメント件数あり、コメント公開許可なし
 *
 * @return void
 */
	public function testFind() {
		//テストデータ
		$type = 'first';
		$query = array(
			'conditions' => array('TestContentCommentBehaviorFindModel.id' => 2),
		);

		$permission = $this->__getPermission('0');
		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Plugin']['key'] = 'plugin_1';
		Current::$current['Permission'] = $permission;

		//テスト実施
		$result = $this->TestModel->find($type, $query);

		//チェック
		//debug($result);
		$this->assertEquals(1, $result['ContentCommentCnt']['cnt']);
		$this->assertArrayNotHasKey('approval_cnt', $result['ContentCommentCnt']);
	}

/**
 * find()のテスト - コメント件数あり、コメント公開許可あり
 *
 * @return void
 */
	public function testFindPublishable() {
		//テストデータ
		$type = 'first';
		$query = array(
			'conditions' => array('TestContentCommentBehaviorFindModel.id' => 2),
		);

		$permission = $this->__getPermission('1');
		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Plugin']['key'] = 'plugin_1';
		Current::$current['Permission'] = $permission;

		//テスト実施
		$result = $this->TestModel->find($type, $query);

		//チェック
		//debug($result);
		$this->assertEquals(2, $result['ContentCommentCnt']['cnt']);
		$this->assertEquals(1, $result['ContentCommentCnt']['approval_cnt']);
	}

/**
 * find()のテスト - コンテンツ0件の時は、空（ContentCommentCnt はつけない）
 *
 * @return void
 */
	public function testFindEmpty() {
		//テストデータ
		$type = 'first';
		$query = array(
			'conditions' => array('TestContentCommentBehaviorFindModel.id' => 999),
		);

		//テスト実施
		$result = $this->TestModel->find($type, $query);

		//チェック
		//debug($result);
		$this->assertEmpty($result);
		//$this->assertEquals(0, $result['ContentCommentCnt']['cnt']);
	}

/**
 * find()のテスト - recursive-1の時、検索結果に ContentCommentCnt はつけない
 *
 * @return void
 */
	public function testFindRecursiveMinus1() {
		//テストデータ
		$type = 'first';
		$query = array(
			'conditions' => array('TestContentCommentBehaviorFindModel.id' => 2),
		);
		$this->TestModel->recursive = -1;

		//テスト実施
		$result = $this->TestModel->find($type, $query);

		//チェック
		//debug($result);
		$this->assertArrayNotHasKey('ContentCommentCnt', $result);
	}

}
