<?php
/**
 * ContentComment Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentComment Helper
 *
 * @package NetCommons\ContentComments\View\Helper
 */
class ContentCommentHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Users.DisplayUser',
	);

/**
 * Default settings
 *
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */
	protected $_defaults = array(
		'fields' => array(
			'use_comment' => 'use_comment',						// コメント利用フラグ
			'use_comment_approval' => 'use_comment_approval',	// コメント承認を使うフラグ
		),
	);

/**
 * Default Constructor
 *
 * @param View $view The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 * @link http://book.cakephp.org/2.0/ja/views/helpers.html#configuring-helpers
 */
	public function __construct(View $view, $settings = array()) {
		$settings = Set::merge($this->_defaults, $settings);

		// $settings初期値(フラグのDB項目名)
//		if (empty($settings)) {
//			$settings = array(
//				'use_comment' => 'use_comment',						// コメント利用フラグ
//				'use_comment_approval' => 'use_comment_approval',	// コメント承認を使うフラグ
//			);
//		}

		parent::__construct($view, $settings);
	}

/**
 * コメント数表示
 *
 * コメント数の表示HTMLを返します。<br>
 * 設定データ配列、コンテンツデータ配列を指定してください。<br>
 * 設定データ配列のuse_commentを判断し、コンテンツデータ配列のContentCommentCnt.cntを表示します。
 *
 * #### Sample code
 * ##### template file(ctp file)
 * ```
 * <?php echo $this->ContentComment->cnt($videoBlockSetting, $video); ?>
 * ```
 *
 * @param array $dbSettings Array of use comment setting data.
 * @param array $content Array of content data with ContentComment count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function count($dbSettings, $content, $attributes = array()) {
		$output = '';

		// コメントを利用する
		if ($dbSettings[$this->settings['use_comment']]) {
			$element = '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> ';
			$element .= (int)Hash::get($content, 'ContentCommentCnt.cnt');
			$attributes = Hash::merge($attributes, array('style' => 'padding-right: 15px;'));

			/* @link http://book.cakephp.org/2.0/ja/core-libraries/helpers/html.html#HtmlHelper::tag */
			$output .= $this->Html->tag('span', $element, $attributes);
		}

		return $output;
	}

/**
 * コメント一覧表示＆コメント登録
 *
 * #### Sample code
 * ##### template file(ctp file)
 * ```
 * <?php echo $this->ContentComment->index('Video', $videoBlockSetting, $video); ?>
 * ```
 *
 * @param string $contentModelName コンテンツのモデル名
 * @param array $dbSettings Array of use comment setting data.
 * @param array $content Array of content data with ContentComment count.
 * @return string HTML tags
 */
	public function index($contentModelName, $dbSettings, $content) {
		$output = '';

		// コメント利用フラグ
		$useComment = Hash::get($dbSettings, $this->settings['use_comment']);

		// コメントを利用する
		if ($useComment) {
			$output .= $this->_View->element('ContentComments.index', array(
				'contentKey' => $content[$contentModelName]['key'],
				'useCommentApproval' => Hash::get($dbSettings, $this->settings['use_comment_approval']),
				'contentCommentCnt' => Hash::get($content, 'ContentCommentCnt.cnt'),
				'contentComments' => $this->request->data('ContentComments'),
			));
		}

		return $output;
	}
}
