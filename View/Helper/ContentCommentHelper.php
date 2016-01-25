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
 * @param array $setting Array of use comment setting data.
 * @param array $content Array of content data with ContentComment count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @param array $settingNames フラグのDB項目名
 * @return string HTML tags
 */
	public function count($setting, $content, $attributes = array(), $settingNames = array()) {
		// $settingNames初期値
		if (empty($settingNames)) {
			$settingNames = array(
				'use_comment' => 'use_comment',
			);
		}
		$output = '';

		// コンテンツコメント利用フラグ
		if (isset($setting[$settingNames['use_comment']]) && $setting[$settingNames['use_comment']]) {
			$element = '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> ';
			$element .= (int)Hash::get($content, 'ContentCommentCnt.cnt');
			$attributes = Hash::merge($attributes, array('style' => 'padding-right: 15px;'));

			// http://book.cakephp.org/2.0/ja/core-libraries/helpers/html.html#HtmlHelper::tag
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
 * @param array $setting Array of use comment setting data.
 * @param array $content Array of content data with ContentComment count.
 * @param array $settingNames フラグのDB項目名
 * @return string HTML tags
 */
	public function index($contentModelName, $setting, $content, $settingNames = array()) {
		// $settingNames初期値
		if (empty($settingNames)) {
			$settingNames = array(
				'use_comment' => 'use_comment',
				'use_comment_approval' => 'use_comment_approval',
			);
		}
		$output = '';

		// 表示するコメント一覧
		$contentComments = $this->__displayContentComments($this->request->data('ContentComments'));

		// コメント利用フラグ
		$useComment = Hash::get($setting, $settingNames['use_comment']);

		/* コメントを利用する */
		if ($useComment) {
			$output .= $this->_View->element('ContentComments.index', array(
				'contentKey' => $content[$contentModelName]['key'],
				'useCommentApproval' => Hash::get($setting, $settingNames['use_comment_approval']),
				'contentCommentCnt' => Hash::get($content, 'ContentCommentCnt.cnt'),
				'contentComments' => $contentComments,
			));
		}

		return $output;
	}

/**
 * 表示するコメント一覧
 *
 * @param array $contentComments コメント一覧
 * @return array 表示するコメント一覧
 */
	private function __displayContentComments($contentComments) {
		$contentComments = $contentComments ? $contentComments : array();

		foreach ($contentComments as $idx => $contentComment) {
			// ・未承認のコメントは表示しない。
			// ・自分のコメントは表示する。
			// ・承認許可ありの場合、表示する。
			if (Current::permission('content_comment_publishable') || $contentComment['ContentComment']['created_user'] == (int)AuthComponent::user('id')) {
				// 表示 => なにもしない
			} elseif ($contentComment['ContentComment']['status'] == WorkflowComponent::STATUS_APPROVED) {
				// 非表示 => 配列から取り除く
				unset($contentComments[$idx]);
			}
		}

		return $contentComments;
	}
}
