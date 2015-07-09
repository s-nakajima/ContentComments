<?php
/**
 * ContentCommentsAppController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * ContentCommentsAppController
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Controller
 * @property Comment $Comment
 * @property FileModel $FileModel
 * @property FileUploadComponent $FileUpload
 * @property ContentCommentsComponent $ContentComments
 * @property NetCommonsBlockComponent $NetCommonsBlock
 * @property NetCommonsFrameComponent $NetCommonsFrame
 * @property NetCommonsWorkflowComponent $NetCommonsWorkflow
 * @property NetCommonsRoomRoleComponent $NetCommonsRoomRole
 * @property PageLayoutComponent $PageLayout
 */
class ContentCommentsAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Security',
		'NetCommons.NetCommonsFrame',		// frameId, frameKey等を自動セット
		'Pages.PageLayout',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		// フレームIDなしはアクセスさせない
		if (!$this->NetCommonsFrame->validateFrameId()) {
			$this->throwBadRequest();
			return false;
		}
	}
}
