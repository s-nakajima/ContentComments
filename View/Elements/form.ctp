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
 * @param bool $isCommentApproved コンテントコメント承認利用フラグ
 * @param string $redirectUrl 操作後の遷移URL
 */
$this->Html->css(
	array('/content_comments/css/style.css'),
	array('plugin' => false, 'once' => true, 'inline' => false)
);
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
					)); ?>
						<?php echo $this->Form->hidden('redirect_url', array('value' => $redirectUrl)); ?>
						<?php echo $this->Form->hidden('plugin_key', array('value' => $pluginKey)); ?>
						<?php echo $this->Form->hidden('content_key', array('value' => $contentKey)); ?>
						<?php echo $this->Form->hidden('is_comment_approved', array('value' => $isCommentApproved)); ?>
						<?php echo $this->Form->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>

						<div class="form-group">
							<div class="input textarea">
								<?php echo $this->Form->textarea(
									'ContentComment.comment',
									array(
										'class' => 'form-control nc-noresize',
										'rows' => 2,
										'default' => '',
								)); ?>
							</div>
						</div>

						<?php /* 登録時入力エラー対応 登録処理のみエラー表示エリア配置 */ ?>
						<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_ADD, $this->request->data)): ?>
<!--							--><?php //echo $this->element(
//								'NetCommons.errors', [
//								'errors' => $this->validationErrors,
//								'model' => 'ContentComment',
//								'field' => 'comment',
//							]); ?>
						<?php endif ?>
						<div class="has-error">
							<?php echo $this->Form->error('ContentComment.comment', null, array('class' => 'help-block')); ?>
						</div>

						<div class="row">
							<div class="col-xs-12 text-center">
								<?php echo $this->Form->button(
									__d('content_comments', 'Comment'),
									array(
										'class' => 'btn btn-success btn-sm',
										'name' => 'process_' . ContentCommentsComponent::PROCESS_ADD,
								)); ?>
							</div>
						</div>
					<?php echo $this->NetCommonsForm->end(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;
