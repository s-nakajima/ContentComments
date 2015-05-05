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

App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('ContentCommentsComponent', 'ContentComments.Controller/Component');
App::uses('AppController', 'Controller');

/**
 * ContentCommentsComponentApp Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Controller\Component
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
class ContentCommentsComponentAppTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
		'plugin.blocks.block',
		'plugin.users.user',
	);

/**
 * ContentComments component
 *
 * @var Component ContentComments component
 */
	public $contentComments = null;

/**
 * Controller for ContentComments component test
 *
 * @var Controller Controller for ContentComments component test
 */
	public $controller = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Configure::write('Config.language', 'ja');

		//テストコントローラ読み込み
		$cakeRequest = new CakeRequest();
		$cakeResponse = new CakeResponse();
		$this->controller = new TestContentCommentsController($cakeRequest, $cakeResponse);
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
		parent::tearDown();

		unset($this->contentComments);
		unset($this->controller);

		Configure::write('Config.language', null);
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
	}
}
