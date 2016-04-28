<?php
/**
 * ContentCommentsComponent::beforeRender()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentsComponent::beforeRender()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\Component\ContentCommentsComponent
 */
class ContentCommentsComponentBeforeRenderTest extends NetCommonsControllerTestCase {

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
 * テストSettingsの取得
 *
 * @return array
 */
	private function __getSettings() {
		$settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'fake.Fake.key',
				'useComment' => 'fakeSetting.use_comment',
			),
			'allow' => array('index'),
		);

		return $settings;
	}

/**
 * beforeRender()のテスト
 *
 * @param array $settings ContentComments->settings
 * @param array $viewVars viewVars
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderGet
 * @return void
 */
	public function testBeforeRender($settings, $viewVars, $exception = null, $return = 'view') {
		$this->controller->ContentComments->settings = $settings;
		$this->controller->viewVars = $viewVars;

		//テスト実行
		$this->_testGetAction('/test_content_comments/test_content_comments_component/index',
				array('method' => 'assertNotEmpty'), $exception, $return);

		//チェック
		//debug($this->view);
		$pattern = '/' . preg_quote('Controller/Component/TestContentCommentsComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
	}

/**
 * アクションのテスト用DataProvider
 *
 * #### 戻り値
 *  - settings: ContentComments->settings
 *  - viewVars: viewVars
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderGet() {
		$settings = $this->__getSettings();

		return array(
			'正常' => array(
				'settings' => $settings,
				'viewVars' => array(
					'fake' => array(
						'Fake' => array('key' => 'key'),
					),
					'fakeSetting' => array('use_comment' => 1),
				),
			),
			'設定なし' => array(
				'settings' => array(),
				'viewVars' => array(),
			),
			'コメントを利用しない(設定なし)' => array(
				'settings' => $settings,
				'viewVars' => array(),
			),
			'コメントを利用しない' => array(
				'settings' => $settings,
				'viewVars' => array(
					'fakeSetting' => array('use_comment' => 0),
				),
			),
			'コンテンツキーのDB項目名なし(設定なし)' => array(
				'settings' => $settings,
				'viewVars' => array(
					'fakeSetting' => array('use_comment' => 1),
				),
			),
			'コンテンツキーのDB項目名なし' => array(
				'settings' => $settings,
				'viewVars' => array(
					'fake' => array(
						'Fake' => array('xxx' => 'key'),
					),
					'fakeSetting' => array('use_comment' => 1),
				),
			),
			'許可アクションなし' => array(
				'settings' => array(
					'viewVarsKey' => array(
						'contentKey' => 'fake.Fake.key',
						'useComment' => 'fakeSetting.use_comment',
					),
					'allow' => array('view'),
				),
				'viewVars' => array(
					'fake' => array(
						'Fake' => array('key' => 'key'),
					),
					'fakeSetting' => array('use_comment' => 1),
				),
			),
		);
	}

/**
 * beforeRender()のPaginator例外テスト
 *
 * @return void
 * @throws InternalErrorException
 */
	public function testBeforeRenderException() {
		$this->generate(
			'TestContentComments.TestContentCommentsComponent', [
				'components' => [
					'Paginator'
				]
			]
		);

		// Exception
		$this->controller->Components->Paginator
			->expects($this->once())
			->method('paginate')
			->will($this->returnCallback(function () {
				throw new InternalErrorException();
			}));

		$settings = $this->__getSettings();
		$viewVars = array(
			'fake' => array(
				'Fake' => array('key' => 'key'),
			),
			'fakeSetting' => array('use_comment' => 1),
		);

		$this->controller->ContentComments->settings = $settings;
		$this->controller->viewVars = $viewVars;

		//テスト実行
		$this->_testGetAction('/test_content_comments/test_content_comments_component/index',
			null, 'InternalErrorException', 'view');
	}
}
