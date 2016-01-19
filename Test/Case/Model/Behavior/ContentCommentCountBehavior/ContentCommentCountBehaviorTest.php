<?php
/**
 * ContentCommentBehavior Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//App::uses('ContentCommentAppTest', 'ContentComments.Test/Case/Model');
App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
//App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * テスト用Fake
 */
class FakeModel extends CakeTestModel {

/**
 * @var array ビヘイビア
 */
	public $actsAs = array('ContentComments.ContentCommentCount');
}

/**
 * Summary for ContentCommentBehavior Test Case
 */
class ContentCommentCountBehaviorTest extends NetCommonsModelTestCase {

/**
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'contentComments';

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
		'plugin.content_comments.fake_model',	// ContentCommentBehaviorTest.php用
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FakeModel = ClassRegistry::init('FakeModel');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FakeModel);
		parent::tearDown();
	}

/**
 * コンテントコメント数あり 公開のみ fields = string
 *
 * @return void
 */
	public function testContentCommentCntOnlyPublish() {
		$conditions = array(
			'key' => 'content_1'
		);
		Current::$current['Plugin']['key'] = 'plugin_1';

		// BehaviorのafterFindでコンテンツコメント数取得
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'conditions' => $conditions,
		));

		$this->assertEquals($fake[0]['ContentCommentCnt']['cnt'], 2);
	}

/**
 * コンテントコメント数あり 公開のみ fields = array
 *
 * @return void
 */
	public function testContentCommentCntFieldsArray() {
		$conditions = array(
			'key' => 'content_1'
		);
		Current::$current['Plugin']['key'] = 'plugin_1';

		// BehaviorのafterFindでコンテンツコメント数取得
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'conditions' => $conditions,
		));

		$this->assertEquals($fake[0]['ContentCommentCnt']['cnt'], 2);
	}

/**
 * コンテントコメント数あり 未承認あり
 *
 * @return void
 */
	public function testContentCommentCntnapproved() {
		$conditions = array(
			'key' => 'content_2'
		);
		Current::$current['Plugin']['key'] = 'plugin_2';

		// BehaviorのafterFindでコンテンツコメント数取得
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'conditions' => $conditions,
		));

		$this->assertEquals($fake[0]['ContentCommentCnt']['cnt'], 1);
	}

/**
 * コンテントコメント数なし = 0
 *
 * @return void
 */
	public function testContentCommentCntNull() {
		$conditions = array(
			'key' => 'content_3'
		);
		Current::$current['Plugin']['key'] = 'plugin_1';

		// BehaviorのafterFindでコンテンツコメント数取得
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'conditions' => $conditions,
		));

		$this->assertEquals($fake[0]['ContentCommentCnt']['cnt'], 0);
	}
}
