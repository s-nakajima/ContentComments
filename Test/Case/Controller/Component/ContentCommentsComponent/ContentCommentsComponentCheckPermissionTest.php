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

App::uses('ContentCommentsComponentAllTestBase', 'ContentComments.Test/Case/Controller/Component');

/**
 * ContentCommentsComponent __checkPermission() 登録、承認 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentCheckPermissionTest extends ContentCommentsComponentAllTestBase {

/**
 * パーミッションがあるかチェック 登録時テスト
 *
 * @return void
 */
	public function testCheckPermissionAdd() {
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'add';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 登録時テスト 投稿許可なし
 *
 * @return void
 */
	public function testCheckPermissionAddNoPermission() {
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '0'; // 投稿許可なし

		$this->controller->action = 'add';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertFalse($rtn);
	}

/**
 * パーミッションがあるかチェック 承認時テスト
 *
 * @return void
 */
	public function testCheckPermissionApproved() {
		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'approve';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertTrue($rtn);
	}

/**
 * パーミッションがあるかチェック 承認時テスト 承認許可なし
 *
 * @return void
 */
	public function testCheckPermissionApprovedNoPermission() {
		Current::$current['Permission']['content_comment_publishable']['value'] = '0'; // 承認許可なし
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->controller->action = 'approve';
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__checkPermission');
		$privateMethod->setAccessible(true);
		$rtn = $privateMethod->invoke($this->contentComments);

		$this->assertFalse($rtn);
	}
}
