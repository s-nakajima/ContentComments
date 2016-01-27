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
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'content_comments';

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
	);

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
		$this->generateNc(Inflector::camelize($this->_controller));
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
 * editアクションのPOSTテスト
 *
 * @param string $method リクエストのmethod(post put delete)
 * @param array $data POSTデータ
 * @param string $role ロール
 * @param array $urlOptions URLオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditPost
 * @return void
 */
	public function testEditPost($method, $data, $role, $urlOptions, $exception = null, $return = 'view') {
		//ログイン
		if (isset($role)) {
			TestAuthGeneral::login($this, $role);
		}

		//テスト実施
		$this->_testPostAction($method, $data, $urlOptions, $exception, $return);

		//正常の場合、リダイレクト
		if (! $exception) {
			$header = $this->controller->response->header();
			$this->assertNotEmpty($header['Location']);
		}

		//ログアウト
		if (isset($role)) {
			TestAuthGeneral::logout($this);
		}
	}

/**
 * editアクションのPOSTテスト用DataProvider
 *
 * #### 戻り値
 *  - method: リクエストのmethod(post put delete)
 *  - data: 登録データ
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditPost() {
		$data = $this->__getData();

		return array(
			'editアクションのPOSTテスト:登録' => array(
				'method' => 'post',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
					),
				)),
				'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
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
				'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array(
					'action' => 'edit',
				),
			),
			'editアクションのPOSTテスト:承認' => array(
				'method' => 'put',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'id' => 3,
					),
				)),
				'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array(
					'action' => 'approve',
				),
			),
			'editアクションのPOSTテスト:削除' => array(
				'method' => 'delete',
				'data' => Hash::merge($data, array(
					'ContentComment' => array(
						'id' => 3,
						'created_user' => 1,
					),
				)),
				'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array(
					'action' => 'delete',
				),
			),
		);
	}

/**
 * editアクションのPOST例外テスト
 *
 * @return void
 */
	public function testEditPostException() {
		$this->setExpectedException('BadRequestException');

		$method = 'put';
		$role = Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR;
		$urlOptions = array(
			'action' => 'edit',
		);
		$exception = null;
		$return = 'view';

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

		//ログイン
		if (isset($role)) {
			TestAuthGeneral::login($this, $role);
		}

		$componentMock = $this->getMock('ContentCommentsComponent', ['comment'], [$this->controller->Components]);
		$componentMock
			->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->controller->Components->set('ContentComments', $componentMock);

		//テスト実施
		$this->_testPostAction($method, $data, $urlOptions, $exception, $return);

		$this->fail('テストNG');
	}
}
