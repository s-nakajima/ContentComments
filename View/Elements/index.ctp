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

echo $this->Html->css('/content_comments/css/style.css', false);
echo $this->Html->script('/content_comments/js/content_comments.js', false);
?>

<?php if ($contentComments): ?>
	<div id="nc-content-comments-<?php echo (int)$frameId; ?>" ng-controller="ContentComments">
		<div class="content-comments">
			<?php $i = 0; ?>
			<?php foreach ($contentComments as $contentComment): ?>

			<?php // ・未承認のコメントは表示しない。
				// ・自分のコメントは表示する。
				// ・承認権限ありの場合、表示する。
				if ($contentCommentPublishable || $contentComment['contentComment']['createdUser'] == (int)AuthComponent::user('id')) {
					// 表示
				} elseif ($contentComment['contentComment']['status'] == ContentComment::STATUS_APPROVED) {
					// 非表示
					continue;
				}
			?>

			<div class="comment <?php echo $i >= ContentCommentsComponent::START_LIMIT ? 'hidden' : '' ?>">
				<div class="row">
					<div class="col-xs-2">
						<?php /* アバター 暫定対応(;'∀') */ ?>
						<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
							<?php echo $this->Html->image('/content_comments/img/avatar.png', array(
								'alt' => $video['userAttributesUser']['value'],
								'width' => '60',
								'height' => '60',
							)); ?>
						</a>
					</div>
					<div class="col-xs-10">
						<div class="row">
							<div class="col-xs-6">
								<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
									<b><?php echo $contentComment['trackableCreator']['username'] ?></b>
								</a>
								<?php /* 公開状況ラベル */ ?>
								<?php echo $this->element('ContentComments.status_label', array('status' => $contentComment['contentComment']['status'])); ?>
							</div>
							<div class="col-xs-6 text-right">
								<small class="text-muted"><?php echo $this->Date->dateFormat($contentComment['contentComment']['created']); ?></small>
							</div>
						</div>

						<?php /* コメント表示 */ ?>
						<div ng-hide="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?>">
							<?php echo nl2br($contentComment['contentComment']['comment']) ?>
						</div>

						<?php /* コンテンツコメント編集権限あり or 自分で投稿したコメントなら、編集可能 */ ?>
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
												if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_EDIT, $this->request->data) &&
													$this->request->data('contentComment.id') == $contentComment['contentComment']['id']) {
													// 何もしない
												} else {
													$contentCommentComment['value'] = nl2br($contentComment['contentComment']['comment']);
												}
												echo $this->Form->textarea('contentComment.comment', $contentCommentComment);
											?>
										</div>
									</div>

									<div class="has-error">
										<?php /* 編集時入力エラー対応 編集処理で、idが同じのみエラー表示エリア配置 */ ?>
										<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_EDIT, $this->request->data) &&
											$this->request->data('contentComment.id') == $contentComment['contentComment']['id']): ?>
											<?php if ($this->validationErrors['contentComment']): ?>
												<?php foreach ($this->validationErrors['contentComment'] as $validationErrors): ?>
													<?php foreach ($validationErrors as $message): ?>
														<div class="help-block">
															<?php echo $message ?>
														</div>
													<?php endforeach ?>
												<?php endforeach ?>
											<?php endif ?>
										<?php endif; ?>
									</div>

									<div class="row">
										<div class="col-xs-12 text-center">
											<button type="button" class="btn btn-default btn-sm" ng-click="isDisplayEdit<?php echo $contentComment['contentComment']['id']; ?> = false;">
												<?php echo __d('content_comments', 'Cancel') ?>
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
					<?php /* 承認権限あり and 未承認のコメント  */ ?>
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

					<?php /* 編集権限あり or 自分で投稿したコメントなら、編集・削除可能 */ ?>
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
									'onclick' => 'return confirm(\'' . sprintf(__d('content_comments', 'Deleting the %s. Are you sure to proceed?'), __d('content_comments', 'comment')) . '\')'
							)); ?>
						<?php echo $this->Form->end(); ?>
					<?php endif; ?>
				</div>
			</div>
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
<?php endif;