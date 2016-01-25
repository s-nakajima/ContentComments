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
 * @param int $contentCommentCnt コンテンツコメント件数
 * @param array $contentComments コンテンツコメント一覧データ
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));

// プラグインキー
$pluginKey = $this->request->params['plugin'];

// 操作後の遷移URL
$redirectUrl = Router::url();
?>
<div class="row">
	<div class="col-xs-12">
		<article>
			<div class="panel panel-default">

				<?php if (Current::permission('content_comment_creatable')): ?>
					<?php /* 登録 */ ?>
					<?php echo $this->element('ContentComments.add', array(
						'pluginKey' => $pluginKey,
						'contentKey' => $contentKey,
						'useCommentApproval' => $useCommentApproval,
						'contentCommentCnt' => $contentCommentCnt,
						'redirectUrl' => $redirectUrl,
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
									'redirectUrl' => $redirectUrl,
									'contentComment' => $contentComment,
								)); ?>

								<div class="text-right" ng-hide="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>">
									<?php /* 承認許可あり and 未承認のコメント  */ ?>
									<?php if (Current::permission('content_comment_publishable') && $contentComment['ContentComment']['status'] == ContentComment::STATUS_APPROVED): ?>
										<?php /* 承認ボタン */ ?>
										<?php echo $this->element('ContentComments.approvalButton', array(
											'pluginKey' => $pluginKey,
											'contentKey' => $contentKey,
											'redirectUrl' => $redirectUrl,
											'contentComment' => $contentComment,
										)); ?>
									<?php endif; ?>

									<?php /* 編集許可あり or 自分で投稿したコメントなら、編集・削除可能 */ ?>
									<?php if (Current::permission('content_comment_editable') || $contentComment['ContentComment']['created_user'] == (int)AuthComponent::user('id')): ?>
										<?php /* 編集・削除ボタン */ ?>
										<?php echo $this->element('ContentComments.editAndDeleteButton', array(
											'pluginKey' => $pluginKey,
											'contentKey' => $contentKey,
											'redirectUrl' => $redirectUrl,
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
