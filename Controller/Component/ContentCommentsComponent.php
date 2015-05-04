<?php
/**
 * ContentComments Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * ContentComments Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Controller\Component
 */
class ContentCommentsComponent extends Component {

/**
 * start limit
 *
 * @var int
 */
	const START_LIMIT = 5;

/**
 * max limit
 *
 * @var int
 */
	const MAX_LIMIT = 100;

/**
 * 登録処理
 *
 * @var string
 */
	const PROCESS_ADD = '1';

/**
 * 編集処理
 *
 * @var string
 */
	const PROCESS_EDIT = '2';

/**
 * 削除処理
 *
 * @var string
 */
	const PROCESS_DELETE = '3';

/**
 * 承認処理
 *
 * @var string
 */
	const PROCESS_APPROVED = '4';

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 * コメントの処理名をパースして取得
 *
 * @throws BadRequestException
 * @return mixed status on success, false on error
 */
	public function parseProcess() {
		if ($matches = preg_grep('/^process_\d/', array_keys($this->controller->data))) {
			list(, $process) = explode('_', array_shift($matches));
		} else {
			if ($this->controller->request->is('ajax')) {
				$this->controller->renderJson(
					['error' => ['validationErrors' => ['status' => __d('net_commons', 'Invalid request.')]]],
					__d('net_commons', 'Bad Request'), 400
				);
			} else {
				throw new BadRequestException(__d('net_commons', 'Bad Request'));
			}
			return false;
		}

		return $process;
	}
}
