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
		'ContentComments.ContentComment',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'ContentComments.ContentComments',
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add' => 'content_comment_creatable',
				'edit' => 'content_comment_creatable',
				'approve' => 'content_comment_publishable',
				'delete' => 'content_comment_creatable'
			),
		),
	);


/**
 * 登録
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// コメントする
			if (!$this->ContentComments->comment()) {
				$this->throwBadRequest();
				return;
			}
			// 一覧へ
			$this->redirect($this->request->data('_tmp.redirect_url'));
		}
	}


/**
 * 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->is('put')) {
			// コメントする
			if (!$this->ContentComments->comment()) {
				$this->throwBadRequest();
				return;
			}
			// 一覧へ
			$this->redirect($this->request->data('_tmp.redirect_url'));
		}
	}

/**
 * 承認
 *
 * @return CakeResponse
 */
	public function approve() {
		if ($this->request->is('put')) {
			// コメントする
			if (!$this->ContentComments->comment()) {
				$this->throwBadRequest();
				return;
			}
			// 一覧へ
			$this->redirect($this->request->data('_tmp.redirect_url'));
		}
	}

/**
 * 削除
 *
 * @return CakeResponse
 */
	public function delete() {
		if ($this->request->is('delete')) {
			// コメントする
			if (!$this->ContentComments->comment()) {
				$this->throwBadRequest();
				return;
			}
			// 一覧へ
			$this->redirect($this->request->data('_tmp.redirect_url'));
		}
	}
}

