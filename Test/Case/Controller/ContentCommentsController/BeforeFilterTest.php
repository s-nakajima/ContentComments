<?php
/**
 * ContentCommentsController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentsController::beforeFilter()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\ContentCommentsController
 */
class ContentCommentsControllerBeforeFilterTest extends NetCommonsControllerTestCase {

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
 * beforeFilter()アクションのGetリクエストテスト - BlockId未設定なので、表示なし
 *
 * @return void
 */
	public function testBeforeFilterGetEmptyRender() {
		//テスト実行
		$this->_testGetAction(array('action' => 'add'), array('method' => 'assertEmpty'), null, 'view');
	}

/**
 * beforeFilter()アクションのPOSTテスト - ビジターまで投稿OKなら、ログインなしでもコメント投稿できる
 *
 * @return void
 */
	public function testBeforeFilterPostVisitor() {
		$data = $this->__getData();

		$data = Hash::merge($data, array(
			'_tmp' => array(
				'is_visitor_creatable' => 1,	// ビジター投稿許可ON
			),
		));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'add'), null, 'view');

		//チェック
		//正常の場合、リダイレクト
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}
}
