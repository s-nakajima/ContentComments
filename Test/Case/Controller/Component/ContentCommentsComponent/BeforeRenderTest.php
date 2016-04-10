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
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'content_comments';

	//public $paginate = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'ContentComments', 'TestContentComments');

		//		$mocks = array('components' => array(
		//			'Paginator',
		//		));

		//テストコントローラ生成
		//$this->generateNc('TestContentComments.TestContentCommentsComponent', $mocks);
		$this->generateNc('TestContentComments.TestContentCommentsComponent');
		//$this->controller->Paginator->settings = array();
		//$this->controller->paginate = array();

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
		//$this->controller->Components->load('Paginator');
		//$this->controller->Paginator->settings = array();

		//テスト実行
		$this->_testGetAction('/test_content_comments/test_content_comments_component/index',
				array('method' => 'assertNotEmpty'), $exception, $return);

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/TestContentCommentsComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//debug($this->view);
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
		return array(
			// エラー：Indirect modification of overloaded property Mock_TestContentCommentsComponentController_591fac1e::$Paginator has no effect
			//         をphpunitで解消できなかったため、対応先送り。phpunitでなく通常の利用ではエラー出ない。
			//			'正常' => array(
			//				'settings' => array(
			//					'viewVarsKey' => array(
			//						'contentKey' => 'fake.Fake.key',
			//						'useComment' => 'fakeSetting.use_comment',
			//					),
			//					'allow' => array('index'),
			//				),
			//				'viewVars' => array(
			//					'fake' => array(
			//						'Fake' => array(
			//							'key' => 'key',
			//						),
			//					),
			//					'fakeSetting' => array(
			//						'use_comment' => 1,
			//					),
			//				),
			//			),
			'設定なし' => array(
				'settings' => array(),
				'viewVars' => array(),
			),
			'コメントを利用しない(設定なし)' => array(
				'settings' => array(
					'viewVarsKey' => array(
						'contentKey' => 'fake.Fake.key',
						'useComment' => 'fakeSetting.use_comment',
					),
					'allow' => array('index'),
				),
				'viewVars' => array(),
			),
			'コメントを利用しない' => array(
				'settings' => array(
					'viewVarsKey' => array(
						'contentKey' => 'fake.Fake.key',
						'useComment' => 'fakeSetting.use_comment',
					),
					'allow' => array('index'),
				),
				'viewVars' => array(
					'fakeSetting' => array(
						'use_comment' => 0,
					),
				),
			),
			'コンテンツキーのDB項目名なし(設定なし)' => array(
				'settings' => array(
					'viewVarsKey' => array(
						'contentKey' => 'fake.Fake.key',
						'useComment' => 'fakeSetting.use_comment',
					),
					'allow' => array('index'),
				),
				'viewVars' => array(
					'fakeSetting' => array(
						'use_comment' => 1,
					),
				),
			),
			'コンテンツキーのDB項目名なし' => array(
				'settings' => array(
					'viewVarsKey' => array(
						'contentKey' => 'fake.Fake.key',
						'useComment' => 'fakeSetting.use_comment',
					),
					'allow' => array('index'),
				),
				'viewVars' => array(
					'fake' => array(
						'Fake' => array(
							'xxx' => 'key',
						),
					),
					'fakeSetting' => array(
						'use_comment' => 1,
					),
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
						'Fake' => array(
							'key' => 'key',
						),
					),
					'fakeSetting' => array(
						'use_comment' => 1,
					),
				),
			),
		);
	}
}
