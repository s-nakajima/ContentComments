<?php
/**
 * ContentCommentHelper::index()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
App::uses('ComponentCollection', 'Controller');
App::uses('SessionComponent', 'Controller/Component');

/**
 * ContentCommentHelper::index()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\View\Helper\ContentCommentHelper
 */
class ContentCommentHelperIndexTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
		'plugin.blocks.block_role_permission',
		'plugin.site_manager.site_setting',
		'plugin.roles.default_role_permission',
		'plugin.roles.role',
		'plugin.rooms.roles_room',
		'plugin.rooms.room_role',
		'plugin.rooms.room_role_permission',
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
 * @param string $useComment 1:ON
 * @param string $useCommentApproval 1:ON
 * @param string $isPermissionEnable 1:ON
 * @return void
 */
	private function __setUp($useComment, $useCommentApproval, $isPermissionEnable) {
		//テストデータ生成
		$viewVars = array(
			'fake' => array(
				'Fake' => array(
					'key' => 'content_1',
					'title' => 'テスト件名',
				),
			),
			'fakeSetting' => array(
				'use_comment' => $useComment,
				'use_comment_approval' => $useCommentApproval,
			),
		);
		$params = array(
			// pagente用
			'paging' => array(
				'Fake' => array(	// model名
					'prevPage' => false,
					'nextPage' => true,
					'paramType' => 'named',
					'pageCount' => 1,
					'options' => array(),
					'page' => 1,
					'count' => 0,
					'limit' => 5,
					'current' => 1,
				),
			),
		);
		$helpers = array(
			'Users.DisplayUser',
			'Workflow.Workflow',
		);
		// --- $requestData
		$ContentCommentModel = ClassRegistry::init('ContentComments.ContentComment');
		$contentComments = $ContentCommentModel->find('all', array(
			'conditions' => array('ContentComment.content_key' => 'publish_key'),
		));
		$requestData['ContentComments'] = $contentComments;

		//Helperロード
		$this->loadHelper('ContentComments.ContentComment', $viewVars, $requestData, $params, $helpers);
		$this->__setHelperSettings();

		/** @see ContentCommentsComponent::comment() */
		$SessionComponent = new SessionComponent(new ComponentCollection());
		$sessionValue = array(
			//'errors' => $this->_controller->ContentComment->validationErrors,
			//'requestData' => $this->_controller->request->data('ContentComment')
			'requestData' => $contentComments[0]['ContentComment'],
		);
		$SessionComponent->write('ContentComments.forRedirect', $sessionValue);

		// --- setCurrent
		$permission = $this->__getPermission($isPermissionEnable);
		Current::$current['Permission'] = $permission;
		Current::$current['Room']['id'] = '1';
		Current::$current['User']['id'] = '1';
	}

/**
 * テストPermissionの取得
 *
 * @param string $isPermissionEnable 1:許可する
 * @return array
 */
	private function __getPermission($isPermissionEnable) {
		$permission = array(
			'content_comment_publishable' => array('value' => $isPermissionEnable),
			'content_comment_editable' => array('value' => $isPermissionEnable),
			'content_comment_creatable' => array('value' => $isPermissionEnable),
		);

		return $permission;
	}

/**
 * ヘルパーのセッティング セット
 *
 * @return void
 */
	private function __setHelperSettings() {
		$this->ContentComment->settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'fake.Fake.key',
				'contentTitleForMail' => 'fake.Fake.title',
				'useComment' => 'fakeSetting.use_comment',
				'useCommentApproval' => 'fakeSetting.use_comment_approval',
			),
		);
	}

/**
 * index()のテスト - パーミッションOFF
 *
 * @return void
 */
	public function testIndex() {
		//データ生成
		$content = array(
			'ContentCommentCnt' => array(
				'approval_cnt' => 1,
			)
		);

		$this->__setUp(1, 1, 0);

		// edit.ctp - コメント承認ありで、公開権限なしの人が公開記事を更新したら、未承認にするテスト
		Current::$current['Permission']['content_comment_publishable'] = 0;

		//テスト実施
		$result = $this->ContentComment->index($content);

		//チェック
		//debug($result);
		$this->assertContains('0 コメント', $result);
		$this->assertContains('1 未承認', $result);
	}

/**
 * index()のテスト - パーミッションON
 *
 * @return void
 */
	public function testIndexPermissionOn() {
		//データ生成
		$content = array(
			'ContentCommentCnt' => array(
				'approval_cnt' => 1,
			)
		);

		$this->__setUp(1, 0, 1);

		//テスト実施
		$result = $this->ContentComment->index($content);

		//チェック
		//debug($result);
		// add.ctp from表示確認
		$this->assertContains('/content_comments/content_comments/add/', $result);
		// edit.ctp from表示確認
		$this->assertContains('/content_comments/content_comments/edit/', $result);
	}

/**
 * index()のテスト - コメントを利用しない場合、nullが返る
 *
 * @return void
 */
	public function testIndexNotUseComment() {
		//テストデータ生成
		$viewVars = array(
			'fake' => array(
				'Fake' => array(
					'key' => 'content_1',
					'title' => 'テスト件名',
				),
			),
			'fakeSetting' => array(
				'use_comment' => 0,
				'use_comment_approval' => 0,
			),
		);
		$requestData = array();
		$params = array();
		$helpers = array();

		//Helperロード
		$this->loadHelper('ContentComments.ContentComment', $viewVars, $requestData, $params, $helpers);
		$this->__setHelperSettings();

		//データ生成
		$content = array();

		//テスト実施
		$result = $this->ContentComment->index($content);

		//チェック
		//debug($result);
		$this->assertNull($result);
	}

/**
 * index()のテスト - コンテンツキーがない場合、nullが返る
 *
 * @return void
 */
	public function testIndexNullContentKey() {
		//テストデータ生成
		$viewVars = array(
			'fake' => array(
				'Fake' => array(
					//'key' => 'content_1',
					'title' => 'テスト件名',
				),
			),
			'fakeSetting' => array(
				'use_comment' => 1,
				'use_comment_approval' => 0,
			),
		);
		$requestData = array();
		$params = array();
		$helpers = array();

		//Helperロード
		$this->loadHelper('ContentComments.ContentComment', $viewVars, $requestData, $params, $helpers);
		$this->__setHelperSettings();

		//データ生成
		$content = array();

		//テスト実施
		$result = $this->ContentComment->index($content);

		//チェック
		//debug($result);
		$this->assertNull($result);
	}

}
