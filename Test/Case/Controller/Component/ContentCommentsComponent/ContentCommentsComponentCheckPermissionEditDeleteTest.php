<?php
/**
 * ContentCommentsComponent __checkPermission() 編集、削除 Test Case
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
App::uses('AuthComponent', 'Controller/Component');

/**
 * ContentCommentsComponent __checkPermission() 編集、削除 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentCheckPermissionEditDeleteTest extends ContentCommentsComponentAppTest {

/**
 * パーミッションがあるかチェック 編集時テスト
 *
 * @return void
 */
	public function testCheckPermissionEdit() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_EDIT; // 編集

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermission() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集許可なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_EDIT; // 編集

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし 自分で投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermissionMyData() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 1,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集許可なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_EDIT; // 編集

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		CakeSession::write('Auth.User.id', null);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし 他人の投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermissionOtherData() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集許可なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_EDIT; // 編集

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		CakeSession::write('Auth.User.id', null);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト
 *
 * @return void
 */
	public function testCheckPermissionDelete() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_DELETE; // 削除

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermission() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集権限なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_DELETE; // 削除

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし 自分で投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermissionMyData() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 1,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集権限なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_DELETE; // 削除

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		CakeSession::write('Auth.User.id', null);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし 他人の投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermissionOtherData() {
		$this->controller->data = array(
			'contentComment' => array(
				'createdUser' => 999,
			)
		);
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => false, // 編集権限なし
			'contentCommentCreatable' => true,
		);
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_DELETE; // 削除

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments, $process);

		CakeSession::write('Auth.User.id', null);

		$this->assertFalse($rtn);
	}
}
