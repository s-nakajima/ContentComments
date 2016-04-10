<?php
/**
 * ContentCommentsComponent::comment()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentCommentsComponent::comment()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\Component\ContentCommentsComponent
 */
class ContentCommentsComponentCommentTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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

		//テストコントローラ生成
		$this->generateNc('TestContentComments.TestContentCommentsComponent');

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __getData() {
		$data = array(
			'ContentComment' => array(
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			)
		);

		return $data;
	}

/**
 * テストPermissionの取得
 *
 * @return array
 */
	private function __getPermission() {
		$permission = array(
			'content_comment_publishable' => array('value' => '1'),
			'content_comment_editable' => array('value' => '1'),
			'content_comment_creatable' => array('value' => '1'),
		);

		return $permission;
	}

/**
 * comment()のテスト
 *
 * @param string $action controller->action
 * @param array $permission Current::permission
 * @param array $expected チェック期待値
 * @param array $data controller->data
 * @param array $requestData request->data
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderGet
 * @return void
 */
	public function testComment($action, $permission, $expected, $data = null, $requestData = null, $exception = null, $return = 'view') {
		//テストアクション実行
		$this->_testGetAction('/test_content_comments/test_content_comments_component/index',
				array('method' => 'assertNotEmpty'), $exception, $return);
		$pattern = '/' . preg_quote('Controller/Component/TestContentCommentsComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->controller->action = $action;
		Current::$current['Permission'] = $permission;
		Current::$current['Block']['key'] = 'block_1';

		if (isset($data)) {
			$this->controller->data = $data;
		}
		if (isset($requestData)) {
			$this->controller->request->data = $requestData;
		}

		//テスト実行
		$result = $this->controller->ContentComments->comment();

		$this->assertEquals($expected, $result);
	}

/**
 * アクションのGETテスト用DataProvider
 *
 * #### 戻り値
 *  - action: controller->action
 *  - permission: Current::permission
 *  - expected: チェック期待値
 *  - data: request->data
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderGet() {
		$data = $this->__getData();
		$permission = $this->__getPermission();

		return array(
			'登録:正常' => array(
				'action' => 'add',
				'permission' => $permission,
				'expected' => true,
				'requestData' => Hash::merge($data, array(
					'ContentComment' => array('comment' => 'Lorem ipsum'),
				)),
			),
			'パーミッションがあるかチェック:全てなし' => array(
				'action' => null,
				'permission' => null,
				'expected' => false,
			),
			'登録：投稿許可ありか:なし' => array(
				'action' => 'add',
				'permission' => array(
					'content_comment_creatable' => array('value' => '0'),
				),
				'expected' => false,
			),
			'登録：投稿許可ありか:ビジター投稿許可あり' => array(
				'action' => 'add',
				'permission' => array(
					'content_comment_creatable' => array('value' => '0'),
				),
				'expected' => true,
				'requestData' => array(
					'_tmp' => array('is_visitor_creatable' => '1'),
				),
			),
			'編集:正常' => array(
				'action' => 'edit',
				'permission' => $permission,
				'expected' => true,
				'requestData' => Hash::merge($data, array(
					'ContentComment' => array('comment' => 'Lorem ipsum'),
				)),
			),
			'編集：編集許可ありか:なし' => array(
				'action' => 'edit',
				'permission' => array(
					'content_comment_editable' => array('value' => '0'),
				),
				'expected' => false,
				'data' => array(
					'ContentComment' => array('created_user' => 'xxx'),
				),
			),
			'編集：編集許可ありか:自分で投稿したコメントなら、編集・削除可能' => array(
				'action' => 'edit',
				'permission' => array(
					'content_comment_editable' => array('value' => '0'),
				),
				'expected' => true,
				'data' => array(
					'ContentComment' => array('created_user' => '1'),
				),
			),
			'承認:正常' => array(
				'action' => 'approve',
				'permission' => $permission,
				'expected' => true,
				'requestData' => $data,
			),
			'削除:正常' => array(
				'action' => 'delete',
				'permission' => $permission,
				'expected' => true,
				'requestData' => Hash::merge($data, array(
					'ContentComment' => array('id' => 1),
				)),
			),
			'削除:削除するコンテンツのidなし' => array(
				'action' => 'delete',
				'permission' => $permission,
				'expected' => false,
				'requestData' => $data,
			),
		);
	}

/**
 * comment()のvalidateエラーテスト
 *
 * @param string $action controller->action
 * @param array $permission Current::permission
 * @param array $data controller->data
 * @param array $requestData request->data
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderGetValidate
 * @return void
 */
	public function testCommentValidate($action, $permission, $data = null, $requestData = null, $exception = null, $return = 'view') {
		//テストアクション実行
		$this->_testGetAction('/test_content_comments/test_content_comments_component/index',
			array('method' => 'assertNotEmpty'), $exception, $return);
		$pattern = '/' . preg_quote('Controller/Component/TestContentCommentsComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->controller->action = $action;

		Current::$current['Permission'] = $permission;
		Current::$current['Block']['key'] = 'block_1';

		if (isset($data)) {
			$this->controller->data = $data;
		}
		if (isset($requestData)) {
			$this->controller->request->data = $requestData;
		}

		//テスト実行
		$result = $this->controller->ContentComments->comment();

		$this->assertTrue($result);

		$validationErrors = $this->controller->ContentComment->validationErrors;
		$this->assertNotEmpty($validationErrors);
		//debug($validationErrors);
	}

/**
 * アクションのGET validateエラーテスト用DataProvider
 *
 * #### 戻り値
 *  - action: controller->action
 *  - permission: Current::permission
 *  - data: request->data
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderGetValidate() {
		$data = $this->__getData();
		$permission = $this->__getPermission();

		return array(
			'登録:validateエラー:空コメント' => array(
				'action' => 'add',
				'permission' => $permission,
				'requestData' => Hash::merge($data, array(
					'ContentComment' => array('comment' => ''),
				)),
			),
			'編集:validateエラー:空コメント' => array(
				'action' => 'edit',
				'permission' => $permission,
				'requestData' => Hash::merge($data, array(
					'ContentComment' => array('comment' => ''),
				)),
			),
		);
	}

}
