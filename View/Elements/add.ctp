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
 * @param string $contentKey コンテンツキー
 * @param string $contentTitleForMail メールのためのコンテンツタイトル
 * @param bool $useCommentApproval コンテントコメント承認利用フラグ
 * @param bool $isVisitorCreatable ビジター投稿許可フラグ
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));
?>
<div class="content-comments">
	<div class="comment-form">
		<div class="media">
			<div class="pull-left">
				<?php /* ログインなしはアバター表示しない */ ?>
				<?php if (AuthComponent::user()): ?>
					<?php /* 自分のアバター */ ?>
					<?php echo $this->DisplayUser->avatarLink(AuthComponent::user(), array(
						'class' => '',
						'alt' => AuthComponent::user('handlename'),
					), [], 'id'); ?>
				<?php endif; ?>

			</div>
			<div class="media-body">
				<?php echo $this->NetCommonsForm->create('ContentComment', array(
					'name' => 'form',
					'url' => '/content_comments/content_comments/add/' . Current::read('Frame.id'),
					'type' => 'post',
				)); ?>
					<?php echo $this->NetCommonsForm->hidden('ContentComment.plugin_key', array('value' => $pluginKey)); ?>
					<?php echo $this->NetCommonsForm->hidden('ContentComment.content_key', array('value' => $contentKey)); ?>
					<?php echo $this->NetCommonsForm->hidden('_mail.content_title', array('value' => $contentTitleForMail)); ?>
					<?php echo $this->NetCommonsForm->hidden('_mail.use_comment_approval', array('value' => $useCommentApproval)); ?>
					<?php echo $this->NetCommonsForm->hidden('_tmp.is_visitor_creatable', array('value' => $isVisitorCreatable)); ?>
					<?php
					// コメント承認機能 0:使わない=>公開 1:使う=>未承認
					$status = $useCommentApproval ? WorkflowComponent::STATUS_APPROVED : WorkflowComponent::STATUS_PUBLISHED;
					if (Current::permission('content_comment_publishable')) {
						// 公開
						$status = WorkflowComponent::STATUS_PUBLISHED;
					}
					echo $this->NetCommonsForm->hidden('ContentComment.status', array('value' => $status));
					?>
					<?php // Block.idのみセットするのは、Controller::beforeFilter() => NetCommonsAppController::beforeFilter() => Current::initialize() => CurrentFrame::initialize() => CurrentFrame::setBlock()
							// でBlock.idないとBlockをfindしてくれないため ?>
					<?php echo $this->NetCommonsForm->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>

					<div class="form-group">
						<div class="input textarea">
							<?php
								/* 登録時入力エラー対応、Sessionのvalueをセット */
								$contentCommentComment = array(
									'class' => 'form-control nc-noresize',
									'rows' => 2,
								);
								if (!$this->Session->read('ContentComments.forRedirect.requestData.id')) {
									$contentCommentComment['value'] = $this->Session->read('ContentComments.forRedirect.requestData.comment');
								}

								echo $this->NetCommonsForm->textarea(
									'ContentComment.comment',
									$contentCommentComment
								);
							?>
						</div>
					</div>

					<?php /* 登録時入力エラー対応 登録処理のみエラー表示エリア配置 */ ?>
					<?php if (!$this->Session->read('ContentComments.forRedirect.requestData.id')): ?>
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
