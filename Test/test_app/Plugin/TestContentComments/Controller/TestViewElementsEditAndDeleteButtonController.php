<?php
/**
 * View/Elements/editAndDeleteButtonテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/editAndDeleteButtonテスト用Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\test_app\Plugin\TestContentComments\Controller
 */
class TestViewElementsEditAndDeleteButtonController extends AppController {

/**
 * editAndDeleteButton
 *
 * @return void
 */
	public function editAndDeleteButton() {
		$this->autoRender = true;
	}

}
