<?php
/**
 * コンテントコメント Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsAppController', 'ContentComments.Controller');

/**
 * コンテントコメント Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Controller
 */
class ContentCommentsController extends ContentCommentsAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'ContentComments.ContentComment',	// コンテンツコメント
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'ContentComments.ContentComments',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('edit'),
				'contentCreatable' => array('edit'),
			),
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * 編集（登録・編集・削除・承認）
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->isPost()) {
			// コメントする
			if (!$this->ContentComments->comment($this->data['ContentComment']['pluginKey'], $this->data['ContentComment']['contentKey'], $this->data['ContentComment']['isCommentApproved'])) {
				$this->throwBadRequest();
				return;
			}
		}

		if (!$this->request->is('ajax')) {
			// 一覧へ
			$this->redirect($this->data['ContentComment']['redirectUrl']);
		}
	}

}

