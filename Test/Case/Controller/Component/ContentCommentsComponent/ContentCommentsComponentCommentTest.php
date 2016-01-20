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

App::uses('ContentCommentsComponentAllTestBase', 'ContentComments.Test/Case/Controller/Component');

/**
 * コメントする Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller\Component\ContentCommentsComponent
 */
class ContentCommentsComponentCommnetTest extends ContentCommentsComponentAllTestBase {

/**
 * コメントする 登録テスト
 *
 * @return void
 */
	public function testCommentAdd() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'process' => ContentCommentsComponent::PROCESS_ADD, // 登録
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->assertTrue($this->contentComments->comment());
	}

/**
 * コメントする 登録テスト 空コメント
 *
 * @return void
 */
	public function testCommentAddNoComment() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'comment' => null, // 空
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'process' => ContentCommentsComponent::PROCESS_ADD, // 登録
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->contentComments->comment();

		//コメントを入力してください
		$this->assertInternalType('string', $this->controller->validationErrors['comment'][0]);
	}

/**
 * コメントする 登録テスト 投稿許可なし
 *
 * @return void
 */
	public function testCommentAddNoPermission() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'comment' => null, // 空
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'process' => ContentCommentsComponent::PROCESS_ADD, // 登録
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '0'; // 投稿許可なし

		$this->contentComments->initialize($this->controller);

		$this->assertFalse($this->contentComments->comment());
	}

/**
 * コメントする 編集テスト
 *
 * @return void
 */
	public function testCommentEdit() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'id' => 1,
				'created_user' => 1,
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'redirect_url' => 'http://localhost/',
				'process' => ContentCommentsComponent::PROCESS_EDIT, // 編集
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->assertTrue($this->contentComments->comment());
	}

/**
 * コメントする 承認テスト
 *
 * @return void
 */
	public function testCommentApproved() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'id' => 3,
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'redirect_url' => 'http://localhost/',
				'process' => ContentCommentsComponent::PROCESS_APPROVED, // 承認
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->assertTrue($this->contentComments->comment());
	}

/**
 * コメントする 削除テスト
 *
 * @return void
 */
	public function testCommentDelete() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'id' => 1,
				'created_user' => 1,
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
			),
			'_tmp' => array(
				'redirect_url' => 'http://localhost/',
				'process' => ContentCommentsComponent::PROCESS_DELETE, // 削除
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->assertTrue($this->contentComments->comment());
	}

/**
 * コメントする 削除失敗テスト id空
 *
 * @return void
 */
	public function testCommentDeleteFail() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'id' => '',
				'created_user' => 1,
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
			),
			'_tmp' => array(
				'redirect_url' => 'http://localhost/',
				'process' => ContentCommentsComponent::PROCESS_DELETE, // 削除
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Block']['key'] = 'block_1';
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		$this->assertFalse($this->contentComments->comment());
	}
}
