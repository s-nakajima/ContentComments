<?php
/**
 * ContentCommentsComponentApp Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('ContentCommentsComponent', 'ContentComments.Controller/Component');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentsComponentApp Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\Component\ContentCommentsComponent
 */
class TestContentCommentsController extends AppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'ContentComments.ContentComment',
	);
}

/**
 * NetCommonsFrame Component test case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentAllTestBase extends NetCommonsControllerTestCase {

/**
 * @var array Fixtures
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
	);

/**
 * @var Component ContentComments component
 */
	public $contentComments = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// PageLayout対応
		NetCommonsControllerTestCase::loadTestPlugin($this, 'NetCommons', 'TestPlugin');

		Configure::write('Config.language', 'ja');

		//テストコントローラ読み込み
		$this->generate(
			'ContentComments.TestContentComments',
			array(
				'components' => array(
					'Auth' => array('user'),
					'Session',
					'Security',
				)
			)
		);
		// $this->controller->request->is('ajax') を成功させるために必要
		$this->controller->request = new CakeRequest();
		$this->controller->response = new CakeResponse();

		// $this->_controller->NetCommons->handleValidationError($this->_controller->ContentComment->validationErrors);対応
		$this->controller->NetCommons->initialize($this->controller);

		//コンポーネント読み込み
		$collection = new ComponentCollection();
		$this->contentComments = new ContentCommentsComponent($collection);
		$this->contentComments->viewSetting = false;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->contentComments);
		unset($this->controller);

		Configure::write('Config.language', null);
		parent::tearDown();
	}
}
