<?php
/**
 * ContentCommentsComponent __checkPermission() 登録、承認 Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentsComponentAppTest', 'ContentComments.Test/Case/Controller/Component/ContentCommentsComponent');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ContentCommentsComponent __checkPermission() 登録、承認 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentCheckPermissionTest extends ContentCommentsComponentAppTest {

/**
 * パーミッションがあるかチェック 登録時テスト
 *
 * @return void
 */
	public function testCheckPermissionAdd() {
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_ADD; // 登録

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 登録時テスト 投稿許可なし
 *
 * @return void
 */
	public function testCheckPermissionAddNoPermission() {
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => false, // 投稿許可なし
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_ADD; // 登録

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 承認時テスト
 *
 * @return void
 */
	public function testCheckPermissionApproved() {
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_APPROVED; // 承認

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 承認時テスト 承認許可なし
 *
 * @return void
 */
	public function testCheckPermissionApprovedNoPermission() {
		$this->controller->viewVars = array(
			'contentCommentPublishable' => false, // 承認許可なし
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_APPROVED; // 承認

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertFalse($rtn);
	}
}
