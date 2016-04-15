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
		'Paginator',
	);

/**
 * コメント数表示
 *
 * コメント数の表示HTMLを返します。<br>
 * 設定データ配列、コンテンツデータ配列を指定してください。<br>
 * 設定データ配列の['viewVarsKey']['useComment']を判断し、コンテンツデータ配列のContentCommentCnt.cntを表示します。
 *
 * #### Sample code
 * ##### template file(ctp file)
 * ```
 * <?php echo $this->ContentComment->count($video); ?>
 * ```
 *
 * @param array $content Array of content data with ContentComment count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function count($content, $attributes = array()) {
		$output = '';
		$useComment = Hash::get($this->_View->viewVars, $this->settings['viewVarsKey']['useComment']);

		// コメントを利用する
		if ($useComment) {
			$element = '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> ';
			// nullを考慮して intにキャスト
			$element .= (int)Hash::get($content, 'ContentCommentCnt.cnt');
			$approvalCnt = (int)Hash::get($content, 'ContentCommentCnt.approval_cnt');

			// 未承認1件以上
			if ($approvalCnt >= 1) {
				$element .= sprintf(__d('content_comments', '（%s unapproved）'), $approvalCnt);
			}

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
 * <?php echo $this->ContentComment->index($video); ?>
 * ```
 *
 * @param array $content Array of content data with ContentComment count.
 * @return string HTML tags
 */
	public function index($content) {
		$output = '';
		$useComment = Hash::get($this->_View->viewVars, $this->settings['viewVarsKey']['useComment']);

		// コメント承認を使う
		$useCommentApproval = Hash::get($this->_View->viewVars, $this->settings['viewVarsKey']['useCommentApproval']);

		// コンテンツキー
		$contentKey = Hash::get($this->_View->viewVars, $this->settings['viewVarsKey']['contentKey']);

		// コメントを利用しない
		if (! $useComment) {
			return;
		}

		// コンテンツキーのDB項目名なし
		if (! isset($contentKey)) {
			return;
		}

		// コメントを利用する
		if ($useComment) {

			$WorkflowComponent = new WorkflowComponent(new ComponentCollection());

			// ビジター投稿許可フラグ
			$permissions = $WorkflowComponent->getBlockRolePermissions(array('content_comment_creatable'));
			$isVisitorCreatable = Hash::get($permissions, 'BlockRolePermissions.content_comment_creatable.visitor.value');

			// 未承認件数
			$approvalCnt = (int)Hash::get($content, 'ContentCommentCnt.approval_cnt');
			// メールのためのコンテンツタイトル
			$contentTitleForMail = Hash::get($this->_View->viewVars, $this->settings['viewVarsKey']['contentTitleForMail']);

			$output .= $this->_View->element('ContentComments.index', array(
				'contentKey' => $contentKey,
				'contentTitleForMail' => $contentTitleForMail,
				'useCommentApproval' => $useCommentApproval,
				'contentComments' => $this->request->data('ContentComments'),
				'approvalCnt' => $approvalCnt,
				'isVisitorCreatable' => $isVisitorCreatable,
			));
		}

		return $output;
	}
}
