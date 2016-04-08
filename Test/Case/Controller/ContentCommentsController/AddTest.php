<?php
/**
 * ContentCommentsController::add()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentsController::add()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\ContentCommentsController
 */
class ContentCommentsControllerAddTest extends NetCommonsControllerTestCase {

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
 * addアクションのPOSTテスト:登録
 *
 * @return void
 */
	public function testAddPost() {
		$data = $this->__getData();

		//テスト実行
		$data = Hash::merge($data, array(
			'ContentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			),
		));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'add'));

		//チェック
		//正常の場合、リダイレクト
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * addアクションのPOST例外テスト
 *
 * @return void
 */
	public function testAddPostException() {
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
		$this->_testPostAction('post', $data, array('action' => 'add'), 'BadRequestException');

		$this->fail('テストNG');
	}

/**
 * addアクションのPOST例外テスト json
 *
 * @return void
 */
	public function testAddPostAjaxFail() {
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
		$result = $this->_testPostAction('post', $data, array('action' => 'add'), 'BadRequestException', 'json');

		// チェック
		// 不正なリクエスト
		$this->assertEquals(400, $result['code']);
	}
}
