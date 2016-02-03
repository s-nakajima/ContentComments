<?php
/**
 * コンテンツコメント一覧 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * @param string $contentKey コンテントキー
 * @param bool $useCommentApproval コンテントコメント承認利用フラグ
 * @param array $contentComments コンテンツコメント一覧データ
 * @param int $approvalCnt 未承認件数
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));

// プラグインキー
$pluginKey = $this->request->params['plugin'];
?>
<div class="row">
	<div class="col-xs-12">
		<article>
			<?php if ($this->Paginator->param('count') >= 1 || Current::permission('content_comment_creatable')): ?>
			<div class="panel panel-default">
			<?php else: ?>
			<div>
			<?php endif; ?>

				<?php /* コメント数 */ ?>
				<div class="content-comments">
					<div class="comment-count">
						<div class="row">
							<div class="col-xs-12">
								<label class="control-label" for="CommentComment">
									<span class="glyphicon glyphicon-comment"></span>
									<?php
										echo sprintf(__d('content_comments', '%s comments'), $this->Paginator->param('count'));

										// 未承認1件以上
										if ($approvalCnt >= 1) {
											echo sprintf(__d('content_comments', '（%s 未承認）'), $approvalCnt);
										}
									?>
								</label>
							</div>
						</div>
					</div>
				</div>

				<?php if (Current::permission('content_comment_creatable')): ?>
					<?php /* 登録 */ ?>
					<?php echo $this->element('ContentComments.add', array(
						'pluginKey' => $pluginKey,
						'contentKey' => $contentKey,
						'useCommentApproval' => $useCommentApproval
					)); ?>
				<?php endif; ?>

				<div id="nc-content-comments-<?php echo Current::read('Frame.id'); ?>">
					<div class="content-comments">
						<?php foreach ($contentComments as $contentComment): ?>
							<?php /* visitar対応 1件目 and 投稿許可なしで border-top 表示しない */ ?>
							<article class="comment">

								<?php /* 1件データ表示＆編集 */ ?>
								<?php echo $this->element('ContentComments.indexOnce', array(
									'pluginKey' => $pluginKey,
									'contentKey' => $contentKey,
									'contentComment' => $contentComment,
								)); ?>

								<div class="text-right" ng-hide="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>">
									<?php /* 承認許可あり and 未承認のコメント  */ ?>
									<?php if (Current::permission('content_comment_publishable') && $contentComment['ContentComment']['status'] == WorkflowComponent::STATUS_APPROVED): ?>
										<?php /* 承認ボタン */ ?>
										<?php echo $this->element('ContentComments.approvalButton', array(
											'pluginKey' => $pluginKey,
											'contentKey' => $contentKey,
											'contentComment' => $contentComment,
										)); ?>
									<?php endif; ?>

									<?php /* 編集許可あり or 自分で投稿したコメントなら、編集・削除可能 */ ?>
									<?php if (Current::permission('content_comment_editable') || $contentComment['ContentComment']['created_user'] == (int)AuthComponent::user('id')): ?>
										<?php /* 編集・削除ボタン */ ?>
										<?php echo $this->element('ContentComments.editAndDeleteButton', array(
											'pluginKey' => $pluginKey,
											'contentKey' => $contentKey,
											'contentComment' => $contentComment,
										)); ?>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach ?>
					</div>

					<?php /* ページャ */ ?>
					<?php echo $this->element('NetCommons.paginator'); ?>

				</div>
			</div>
		</article>
	</div>
</div>
