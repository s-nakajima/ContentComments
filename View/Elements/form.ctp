<?php
/**
 * コンテンツコメント登録 template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテントキー
 * @param bool $useCommentApproval コンテントコメント承認利用フラグ
 * @param string $redirectUrl 操作後の遷移URL
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));
?>

<?php if (Current::permission('content_comment_creatable')): ?>
	<div class="content-comments">
		<div class="comment-form">
			<div class="row">
				<div class="col-xs-12">
				<label class="control-label" for="CommentComment">
					<span class="glyphicon glyphicon-comment"></span>
					<?php echo sprintf(__d('content_comments', '%s comments'), isset($contentCommentCnt) ? $contentCommentCnt : 0) ?>
				</label>
				</div>
			</div>
			<div class="media">
				<div class="pull-left">
					<?php /* アバター 暫定対応(;'∀') */ ?>
					<?php echo $this->Html->image('/content_comments/img/avatar.png', array(
						'class' => 'media-object',
						'alt' => AuthComponent::user('username'),
						'width' => '60',
						'height' => '60',
					)); ?>
				</div>
				<div class="media-body">
					<?php echo $this->NetCommonsForm->create('ContentComment', array(
						'name' => 'form',
						'url' => '/content_comments/content_comments/edit/' . Current::read('Frame.id'),
						'type' => 'post',
					)); ?>
						<?php echo $this->NetCommonsForm->hidden('ContentComment.plugin_key', array('value' => $pluginKey)); ?>
						<?php echo $this->NetCommonsForm->hidden('ContentComment.content_key', array('value' => $contentKey)); ?>
						<?php echo $this->NetCommonsForm->hidden('_tmp.redirect_url', array('value' => $redirectUrl)); ?>
						<?php echo $this->NetCommonsForm->hidden('_tmp.process', array('value' => ContentCommentsComponent::PROCESS_ADD)); ?>
						<?php
						if (Current::permission('content_comment_publishable')) {
							// 公開
							$status = ContentComment::STATUS_PUBLISHED;
						} else {
							// コメント承認機能 0:使わない=>公開 1:使う=>未承認
							$status = $useCommentApproval ? ContentComment::STATUS_APPROVED: ContentComment::STATUS_PUBLISHED;
						}
						echo $this->NetCommonsForm->hidden('ContentComment.status', array('value' => $status));
						?>
						<?php // Block.idのみセットするのは、Controller::beforeFilter() => NetCommonsAppController::beforeFilter() => Current::initialize() => CurrentFrame::initialize() => CurrentFrame::setBlock()
								// でBlock.idないとBlockをfindしてくれないため ?>
						<?php echo $this->NetCommonsForm->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>

						<div class="form-group">
							<div class="input textarea">
								<?php echo $this->NetCommonsForm->textarea(
									'ContentComment.comment',
									array(
										'class' => 'form-control nc-noresize',
										'rows' => 2,
										'default' => '',
								)); ?>
							</div>
						</div>

						<?php /* 登録時入力エラー対応 登録処理のみエラー表示エリア配置 */ ?>
						<?php if ($this->request->data('_tmp.process') == ContentCommentsComponent::PROCESS_ADD): ?>
							<div class="has-error">
								<?php echo $this->NetCommonsForm->error('ContentComment.comment', null, array('class' => 'help-block')); ?>
							</div>
						<?php endif ?>

						<div class="row">
							<div class="col-xs-12 text-center">
								<?php echo $this->NetCommonsForm->button(
									__d('content_comments', 'Comment'),
									array(
										'class' => 'btn btn-success btn-sm',
								)); ?>
							</div>
						</div>
					<?php echo $this->NetCommonsForm->end(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;
