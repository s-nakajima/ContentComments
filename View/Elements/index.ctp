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

$this->Html->css(
	array('/content_comments/css/style.css'),
	array('plugin' => false, 'once' => true, 'inline' => false)
);
$this->Html->script(
	array('/content_comments/js/content_comments.js'),
	array('plugin' => false, 'once' => true, 'inline' => false)
);

$contentComments = isset($contentComments) ? $contentComments : array();
?>

<?php
foreach ($contentComments as $idx => $contentComment) {
	// ・未承認のコメントは表示しない。
	// ・自分のコメントは表示する。
	// ・承認許可ありの場合、表示する。
	if ($contentCommentPublishable || $contentComment['contentComment']['createdUser'] == (int)AuthComponent::user('id')) {
		// 表示 => なにもしない
	} elseif ($contentComment['contentComment']['status'] == ContentComment::STATUS_APPROVED) {
		// 非表示 => 配列から取り除く
		unset($contentComments[$idx]);
	}
} ?>

<?php /* コメントを利用しない or (コメント0件 and コメント投稿できない) */ ?>
<?php if (!$useComment || (!$contentComments && !$contentCommentCreatable)): ?>
	<?php /* 表示しない */ ?>

<?php else : ?>
    <article>
		<div class="panel panel-default">

			<?php /* 入力欄 */ ?>
			<?php echo $this->element('ContentComments.form', array(
				'formName' => $formName,
				'contentCommentCnt' => $contentCommentCnt,
			)); ?>

			<div id="nc-content-comments-<?php echo (int)$frameId; ?>" ng-controller="ContentComments">
				<div class="content-comments">
					<?php $i = 0; ?>
					<?php foreach ($contentComments as $contentComment): ?>
						<?php /* visitar対応 1件目 and 投稿許可なしで border-top 表示しない */ ?>
						<article class="comment <?php echo $i >= ContentCommentsComponent::START_LIMIT ? 'hidden' : '' ?>
									 <?php echo $i == 0 && !$contentCommentCreatable ? 'comment-no-form' : ''; ?>">
							<div class="media">
								<div class="pull-left">
									<?php /* アバター 暫定対応(;'∀') */ ?>
									<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
										<?php echo $this->Html->image('/content_comments/img/avatar.png', array(
											'class' => 'media-object',
											'alt' => $contentComment['trackableCreator']['username'],
											'width' => '60',
											'height' => '60',
										)); ?>
									</a>
								</div>
								<div class="media-body">
									<div class="row">
										<div class="col-xs-6">
											<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
												<b><?php echo $contentComment['trackableCreator']['username'] ?></b>
											</a>
											<?php /* 公開状況ラベル */ ?>
											<?php echo $this->element('NetCommons.status_label', array(
												'labels' => [
													ContentComment::STATUS_APPROVED => [
														'class' => 'label-warning',
														'message' => __d('content_comments', 'Approving'),
													],
												],
												'status' => $contentComment['contentComment']['status']
											)); ?>
										</div>
										<div class="col-xs-6 text-right">
											<small class="text-muted"><?php echo $this->Date->dateFormat($contentComment['contentComment']['created']); ?></small>
										</div>
									</div>

									<?php /* コメント表示 */ ?>
									<div ng-hide="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?>">
										<?php echo nl2br($contentComment['contentComment']['comment']) ?>
									</div>

									<?php /* コンテンツコメント編集許可あり or 自分で投稿したコメントなら、編集可能 */ ?>
									<?php if ($contentCommentEditable || $contentComment['contentComment']['createdUser'] == (int)AuthComponent::user('id')): ?>
										<?php /* 編集フォーム 非表示 */ ?>
										<div ng-show="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?>">
											<?php echo $this->Form->create($formName, array(
												'name' => 'form',
											)); ?>
												<?php echo $this->Form->hidden('contentComment.id', array(
													'value' => $contentComment['contentComment']['id'],
												)); ?>
												<?php echo $this->Form->hidden('contentComment.createdUser', array(
													'value' => $contentComment['contentComment']['createdUser'],
												)); ?>
												<div class="form-group">
													<div class="input textarea">
														<?php
														$contentCommentComment = array(
															'class' => 'form-control nc-noresize',
															'rows' => 2,
															'default' => nl2br($contentComment['contentComment']['comment']),
														);

														/* 編集時入力エラー対応 編集処理で、idが同じのみvalueをセットしない */
														$isCommentValueSet = true;
														if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_EDIT, $this->request->data) &&
															$this->request->data('contentComment.id') == $contentComment['contentComment']['id']) {
															$isCommentValueSet = false;
														}
														if ($isCommentValueSet) {
															$contentCommentComment['value'] = nl2br($contentComment['contentComment']['comment']);
														}

														echo $this->Form->textarea('contentComment.comment', $contentCommentComment);
														?>
													</div>
												</div>

												<?php /* 編集時入力エラー対応 編集処理で、idが同じのみエラー表示エリア配置 */ ?>
												<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_EDIT, $this->request->data) &&
													$this->request->data('contentComment.id') == $contentComment['contentComment']['id']): ?>
														<?php echo $this->element(
															'NetCommons.errors', [
															'errors' => $this->validationErrors,
															'model' => 'ContentComment',
															'field' => 'comment',
														]); ?>
												<?php endif ?>

												<div class="row">
													<div class="col-xs-12 text-center">
														<button type="button" class="btn btn-default btn-sm" ng-click="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?> = false;">
															<?php echo __d('net_commons', 'Cancel') ?>
														</button>
														<?php echo $this->Form->button(
															__d('content_comments', 'Comment'),
															array(
																'class' => 'btn btn-success btn-sm',
																'name' => 'process_' . ContentCommentsComponent::PROCESS_EDIT,
														)); ?>
													</div>
												</div>
											<?php echo $this->Form->end(); ?>
										</div>
									<?php endif; ?>

								</div>
							</div>
							<div class="text-right" ng-hide="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?>">
								<?php /* 承認許可あり and 未承認のコメント  */ ?>
								<?php if ($contentCommentPublishable && $contentComment['contentComment']['status'] == ContentComment::STATUS_APPROVED): ?>
									<?php /* 承認 */ ?>
									<?php echo $this->Form->create($formName, array(
										'name' => 'form',
										'style' => 'display: inline;',
									)); ?>
										<?php echo $this->Form->hidden('contentComment.id', array(
											'value' => $contentComment['contentComment']['id'],
										)); ?>
										<?php echo $this->Form->button(
											"<span class='glyphicon glyphicon-ok'></span>",
											array(
												'class' => 'btn btn-warning btn-sm',
												'name' => 'process_' . ContentCommentsComponent::PROCESS_APPROVED,
												'onclick' => 'return confirm(\'' . sprintf(__d('content_comments', 'Approving the %s. Are you sure to proceed?'), __d('content_comments', 'comment')) . '\')'
										)); ?>
									<?php echo $this->Form->end(); ?>
								<?php endif; ?>

								<?php /* 編集許可あり or 自分で投稿したコメントなら、編集・削除可能 */ ?>
								<?php if ($contentCommentEditable || $contentComment['contentComment']['createdUser'] == (int)AuthComponent::user('id')): ?>
									<?php /* 編集 */ ?>
									<?php /* 編集の表示・非表示フラグ 非表示 */ ?>
									<input class="hide" type="checkbox" ng-model="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?>"
										<?php /* 編集時入力エラー対応　編集処理で、idが同じなら編集画面を開く */ ?>
										<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_EDIT, $this->request->data) &&
											$this->request->data('contentComment.id') == $contentComment['contentComment']['id']): ?>
											ng-init="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?> = true;"
										<?php endif; ?>>
									<button type="button" class="btn btn-primary btn-sm" ng-click="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?> = true;">
										<span class='glyphicon glyphicon-edit'></span>
									</button>

									<?php /* 削除 */ ?>
									<?php echo $this->Form->create($formName, array(
										'name' => 'form',
										'style' => 'display: inline;',
									)); ?>
										<?php echo $this->Form->hidden('contentComment.id', array(
											'value' => $contentComment['contentComment']['id'],
										)); ?>
										<?php echo $this->Form->hidden('contentComment.createdUser', array(
											'value' => $contentComment['contentComment']['createdUser'],
										)); ?>
										<?php echo $this->Form->button(
											"<span class='glyphicon glyphicon-trash'></span>",
											array(
												'class' => 'btn btn-danger btn-sm',
												'name' => 'process_' . ContentCommentsComponent::PROCESS_DELETE,
												'onclick' => 'return confirm(\'' . sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('content_comments', 'comment')) . '\')'
										)); ?>
									<?php echo $this->Form->end(); ?>
								<?php endif; ?>
							</div>
						</article>
						<?php $i++; ?>
					<?php endforeach ?>

					<?php /* もっと見る */ ?>
					<div class="comment-more">
						<button type="button" class="btn btn-info btn-block more <?php echo $i <= ContentCommentsComponent::START_LIMIT ? 'hidden' : '' ?>"
								ng-click="more();">
							<?php echo h(__d('net_commons', 'More')); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
    </article>
<?php endif;
