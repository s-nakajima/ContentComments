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

App::uses('ContentCommentAppTest', 'ContentComments.Test/Case/Model');

/**
 * テスト用Fake
 */
class FakeModel extends CakeTestModel {

/**
 * @var array ビヘイビア
 */
	public $actsAs = array('ContentComments.ContentComment');
}

/**
 * Summary for ContentCommentBehavior Test Case
 */
class ContentCommentBehaviorTest extends ContentCommentAppTest {

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
 * コンテントコメント数あり 公開のみ
 *
 * @return void
 */
	public function testContentCommentCntOnlyPublish() {
		$conditions = array(
			'key' => 'content_1'
		);
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'fields' => '*, ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			'conditions' => $conditions,
		));

		$this->assertEqual($fake[0]['ContentCommentCnt']['cnt'], 2);
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
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'fields' => '*, ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			'conditions' => $conditions,
		));

		$this->assertEqual($fake[0]['ContentCommentCnt']['cnt'], 1);
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
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			'fields' => '*, ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			'conditions' => $conditions,
		));

		$this->assertEqual($fake[0]['ContentCommentCnt']['cnt'], 0);
	}

/**
 * コンテントコメント数を取得しない
 *
 * @return void
 */
	public function testContentCommentCntNoGet() {
		$conditions = array(
			'key' => 'content_1'
		);
		$fake = $this->FakeModel->find('all', array(
			'recursive' => 1,
			//'fields' => '*, ContentCommentCnt.cnt',	// Behaviorでコンテンツコメント数取得
			'conditions' => $conditions,
		));
		$this->assertFalse(isset($fake[0]['ContentCommentCnt']));
	}
}
