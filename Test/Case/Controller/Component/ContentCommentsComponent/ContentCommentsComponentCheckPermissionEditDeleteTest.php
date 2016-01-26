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
App::uses('ContentCommentsComponentAllTestBase', 'ContentComments.Test/Case/Controller/Component');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ContentCommentsComponent __checkPermission() 編集、削除 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentCheckPermissionEditDeleteTest extends ContentCommentsComponentAllTestBase {

/**
 * パーミッションがあるかチェック 編集時テスト
 *
 * @return void
 */
	public function testCheckPermissionEdit() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'edit';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermission() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'edit';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし 自分で投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermissionMyData() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 1,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'edit';
		$this->contentComments->initialize($this->controller);

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		CakeSession::write('Auth.User.id', null);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 編集時テスト 編集権限なし 他人の投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionEditNoPermissionOtherData() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'edit';
		$this->contentComments->initialize($this->controller);

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		CakeSession::write('Auth.User.id', null);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト
 *
 * @return void
 */
	public function testCheckPermissionDelete() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'delete';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermission() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'delete';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし 自分で投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermissionMyData() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 1,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'delete';
		$this->contentComments->initialize($this->controller);

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		CakeSession::write('Auth.User.id', null);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 削除時テスト 編集許可なし 他人の投稿したコメント
 *
 * @return void
 */
	public function testCheckPermissionDeleteNoPermissionOtherData() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'created_user' => 999,
			)
		);
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '0'; // 編集許可なし
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'delete';
		$this->contentComments->initialize($this->controller);

		CakeSession::write('Auth.User.id', 1); // ログインしているユーザID

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		CakeSession::write('Auth.User.id', null);

		$this->assertFalse($rtn);
	}
}
