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

<?php if ($contentCommentCreatable): ?>
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
					<?php echo $this->Form->create('ContentComment', array(
						'name' => 'form',
						'url' => '/content_comments/content_comments/edit/' . $frameId,
						'novalidate' => true,
					)); ?>
						<?php echo $this->Form->hidden('redirectUrl', array('value' => $redirectUrl)); ?>
						<?php echo $this->Form->hidden('pluginKey', array('value' => $pluginKey)); ?>
						<?php echo $this->Form->hidden('contentKey', array('value' => $contentKey)); ?>
						<?php echo $this->Form->hidden('isCommentApproved', array('value' => $isCommentApproved)); ?>

						<div class="form-group">
							<div class="input textarea">
								<?php echo $this->Form->textarea(
									'contentComment.comment',
									array(
										'class' => 'form-control nc-noresize',
										'rows' => 2,
										'default' => '',
								)); ?>
							</div>
						</div>

						<?php /* 登録時入力エラー対応 登録処理のみエラー表示エリア配置 */ ?>
						<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_ADD, $this->request->data)): ?>
							<?php echo $this->element(
								'NetCommons.errors', [
								'errors' => $this->validationErrors,
								'model' => 'ContentComment',
								'field' => 'comment',
							]); ?>
						<?php endif ?>

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
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;
