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
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'content_comment_creatable',
			),
		),
		'Session',
	);

/**
 * 編集（登録・編集・削除・承認）
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->isPost()) {
			// コメントする
			if (!$this->ContentComments->comment($this->data['ContentComment']['plugin_key'], $this->data['ContentComment']['content_key'], $this->data['ContentComment']['is_comment_approved'])) {
				$this->throwBadRequest();
				return;
			}

			// 一覧へ
			$this->redirect($this->data['ContentComment']['redirect_url']);
		}
	}

}

