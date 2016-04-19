<?php
/**
 * ContentCommentBehavior::delete()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('TestContentCommentBehaviorDeleteModelFixture', 'ContentComments.Test/Fixture');

/**
 * ContentCommentBehavior::delete()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\Behavior\ContentCommentBehavior
 */
class ContentCommentBehaviorDeleteTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.test_content_comment_behavior_delete_model',
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
		$this->TestModel = ClassRegistry::init('TestContentComments.TestContentCommentBehaviorDeleteModel');
	}

/**
 * delete()のテスト
 *
 * @return void
 */
	public function testDelete() {
		//テスト実施
		$result = $this->TestModel->delete(1);

		//チェック
		//debug($result);
		$this->assertTrue($result);
	}

/**
 * deleteAll()のテスト
 *
 * @return void
 */
	public function testDeleteAll() {
		$conditions = array(
			'TestContentCommentBehaviorDeleteModel.key' => 'publish_key'
		);

		//テスト実施
		$result = $this->TestModel->deleteAll($conditions, true, true);

		//チェック
		//debug($result);
		$this->assertTrue($result);
	}
}
