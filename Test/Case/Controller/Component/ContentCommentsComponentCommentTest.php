<?php
/**
 * コメントする Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentsComponentAppTest', 'ContentComments.Test/Case/Controller/Component');

/**
 * コメントする Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentCommnetTest extends ContentCommentsComponentAppTest {

/**
 * コメントする 登録テスト
 *
 * @return void
 */
	public function testCommentAdd() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_ADD => '', // 登録
			'contentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertTrue($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする 登録テスト 空コメント
 *
 * @return void
 */
	public function testCommentAddNoComment() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_ADD => '', // 登録
			'contentComment' => array(
				'comment' => null,
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved);

		//コメントを入力してください
		$this->assertInternalType('string', $this->controller->validationErrors['comment'][0]);
	}

/**
 * コメントする 登録テスト 投稿許可なし
 *
 * @return void
 */
	public function testCommentAddNoPermission() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_ADD => '', // 登録
			'contentComment' => array(
				'comment' => null,
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => false, // 投稿許可なし
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertFalse($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする 編集テスト
 *
 * @return void
 */
	public function testCommentEdit() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_EDIT => '', // 編集
			'contentComment' => array(
				'id' => 1,
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertTrue($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする 承認テスト
 *
 * @return void
 */
	public function testCommentApproved() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_APPROVED => '', // 承認
			'contentComment' => array(
				'id' => 3,
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertTrue($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする 削除テスト
 *
 * @return void
 */
	public function testCommentDelete() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_DELETE => '', // 削除
			'contentComment' => array(
				'id' => 1,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertTrue($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする 削除失敗テスト id空
 *
 * @return void
 */
	public function testCommentDeleteFail() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_DELETE => '', // 削除
			'contentComment' => array(
				'id' => '',
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertFalse($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}

/**
 * コメントする Ajaxで __parseProcess() でパース失敗テスト
 *
 * @return void
 */
	public function testCommentAjaxFailParseProcess() {
		$this->controller->data = array(
			'process_xxx' => '',
			'contentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest'; // ajax通信

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->assertFalse($this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved));
	}
}
