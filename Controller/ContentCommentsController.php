<?php
/**
 * ContentComments Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsAppController', 'ContentComments.Controller');

/**
 * ContentComments Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package app.Plugin.ContentComments.Controller
 */
class ContentCommentsController extends ContentCommentsAppController {

/**
 * use model
 *
 * @var array
 */
	//public $uses = array();

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * index
 *
 * @param int $frameId frames.id
 * @param string $lang language
 * @return CakeResponse
 */
	//public function index($frameId = 0, $lang = '') {
	//}

}
