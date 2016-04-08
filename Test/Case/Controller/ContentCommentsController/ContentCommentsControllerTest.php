<?php
/**
 * ContentCommentsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('ContentCommentsComponent', 'ContentComments.Controller/Component');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentCommentsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Announcements\Test\Case\Controller
 */
class ContentCommentsControllerTest extends NetCommonsControllerTestCase {

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
 * @var array
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
 * アクションのPOSTテスト
 *
 * @param string $method リクエストのmethod(post put delete)
 * @param array $data POSTデータ
 * @param array $urlOptions URLオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderPost
 * @return void
 */
	public function testPost($method, $data, $urlOptions, $exception = null, $return = 'view') {
		//テスト実施
		$this->_testPostAction($method, $data, $urlOptions, $exception, $return);

		//正常の場合、リダイレクト
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * editアクションのPOSTテスト用DataProvider
 *
 * #### 戻り値
 *  - method: リクエストのmethod(post put delete)
 *  - data: 登録データ
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderPost() {
		$data = $this->__getData();

		return array(
			'addアクションのPOSTテスト:登録' => array(
				'method' => 'post',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					),
				)),
				'urlOptions' => array(
					'action' => 'add',
				)
			),
			'editアクションのPOSTテスト:編集' => array(
				'method' => 'put',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'id' => 1,
						'created_user' => 1,
						'comment' => 'edit......................',
					),
				)),
				'urlOptions' => array(
					'action' => 'edit',
				),
			),
			'approveアクションのPOSTテスト:承認' => array(
				'method' => 'put',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'id' => 3,
						'status' => WorkflowComponent::STATUS_APPROVED,	// 承認依頼
					),
				)),
				'urlOptions' => array(
					'action' => 'approve',
				),
			),
			'deleteアクションのPOSTテスト:削除' => array(
				'method' => 'delete',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'id' => 3,
						'created_user' => 1,
					),
				)),
				'urlOptions' => array(
					'action' => 'delete',
				),
			),
		);
	}

/**
 * addアクションのPOST例外テスト
 *
 * @param string $method リクエストのmethod(post put delete)
 * @param array $data POSTデータ
 * @param array $urlOptions URLオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderPost
 * @return void
 */
	public function testPostException($method, $data, $urlOptions, $exception = null, $return = 'view') {
		$componentMock = $this->getMock('ContentCommentsComponent', ['comment'], [$this->controller->Components]);
		$componentMock
			->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->controller->Components->set('ContentComments', $componentMock);

		//テスト実施
		$this->_testPostAction($method, $data, $urlOptions, 'BadRequestException', $return);

		$this->fail('テストNG');
	}

/**
 * addアクションのPOST例外テスト json
 *
 * @param string $method リクエストのmethod(post put delete)
 * @param array $data POSTデータ
 * @param array $urlOptions URLオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderPost
 * @return void
 */
	public function testAddPostAjaxFail($method, $data, $urlOptions, $exception = null, $return = 'view') {
		$componentMock = $this->getMock('ContentCommentsComponent', ['comment'], [$this->controller->Components]);
		$componentMock
			->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->controller->Components->set('ContentComments', $componentMock);

		//テスト実施
		$result = $this->_testPostAction($method, $data, $urlOptions, 'BadRequestException', 'json');

		// チェック
		// 不正なリクエスト
		$this->assertEquals(400, $result['code']);
	}
}
