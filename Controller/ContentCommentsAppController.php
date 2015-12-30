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
 * @property FileModel $FileModel
 * @property FileUploadComponent $FileUpload
 * @property ContentCommentsComponent $ContentComments
 * @property WorkflowComponent $Workflow
 * @property PageLayoutComponent $PageLayout
 * @property PermissionComponent $Permission
 */
class ContentCommentsAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Pages.PageLayout',
		'Security',
	);
}
