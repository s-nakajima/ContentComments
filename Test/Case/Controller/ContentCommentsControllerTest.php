<?php
/**
 * ContentCommentsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsController', 'ContentComments.Controller');

/**
 * ContentCommentsController Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.ContentComments.Test.Controller.Case
 */
class ContentCommentsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.Session',
		'app.SiteSetting',
		'app.SiteSettingValue',
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
	}

/**
 * test index
 *
 * @return void
 */
	//public function testIndex() {
	//	$frameId = 1;
	//	$this->testAction('/content_comments/content_comments/index/' . $frameId . '/', array('method' => 'get'));
	//	$this->assertTextNotContains('ERROR', $this->view);
	//}

}
