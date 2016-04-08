<?php
/**
 * ContentCommentsController::approve()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentsController::approve()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\ContentCommentsController
 */
class ContentCommentsControllerApproveTest extends NetCommonsControllerTestCase {

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
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'content_comments';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

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
			'Frame' => array(
				'id' => '6'
			),
			'Block' => array(
				'id' => 'Block_1',
			),
			'ContentComment' => array(
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			)
		);

		return $data;
	}

/**
 * approveアクションのPOSTテスト:承認
 *
 * @return void
 */
	public function testApprovePost() {
		$data = $this->__getData();

		//テスト実行
		$data = Hash::merge($data, array(
			'ContentComment' => array(
				'id' => 3,
			),
		));

		//テスト実行
		$this->_testPostAction('put', $data, array('action' => 'approve'), null, 'view');

		//チェック
		//正常の場合、リダイレクト
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * approveアクションのPOST例外テスト
 *
 * @return void
 */
	public function testApprovePostException() {
		$data = $this->__getData();

		$componentMock = $this->getMock('ContentCommentsComponent', ['comment'], [$this->controller->Components]);
		$componentMock->settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'xxx',
				'useComment' => 'xxx',
			),
			'allow' => array('view'),
		);
		$componentMock
			->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->controller->Components->set('ContentComments', $componentMock);

		//テスト実施
		$this->_testPostAction('put', $data, array('action' => 'approve'), 'BadRequestException', 'view');

		$this->fail('テストNG');
	}

/**
 * approveアクションのPOST例外テスト json
 *
 * @return void
 */
	public function testApprovePostAjaxFail() {
		$data = $this->__getData();

		$componentMock = $this->getMock('ContentCommentsComponent', ['comment'], [$this->controller->Components]);
		$componentMock->settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'xxx',
				'useComment' => 'xxx',
			),
			'allow' => array('view'),
		);
		$componentMock
			->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->controller->Components->set('ContentComments', $componentMock);

		//テスト実施
		$result = $this->_testPostAction('put', $data, array('action' => 'approve'), 'BadRequestException', 'json');

		// チェック
		// 不正なリクエスト
		$this->assertEquals(400, $result['code']);
	}
}
