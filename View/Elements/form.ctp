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
 * @param string $formName フォーム名
 */
?>
<?php echo $this->Html->css('/content_comments/css/style.css', false); ?>

<?php if ($contentCommentCreatable): ?>
	<div class="content-comments">
		<div class="comment-form">
			<div class="row">
				<div class="col-xs-12">
				<label class="control-label" for="CommentComment">
					<span class="glyphicon glyphicon-comment"></span>
					<?php echo sprintf(__d('content_comments', '%s comments'), '999') ?>
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
					<?php echo $this->Form->create($formName, array(
						'name' => 'form',
						'novalidate' => true,
					)); ?>
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

						<div class="has-error">
							<?php /* 登録時入力エラー対応 登録処理のみエラー表示エリア配置 */ ?>
							<?php if (array_key_exists('process_' . ContentCommentsComponent::PROCESS_ADD, $this->request->data)): ?>
								<?php if ($this->validationErrors['contentComment']): ?>
									<?php foreach ($this->validationErrors['contentComment'] as $validationErrors): ?>
										<?php foreach ($validationErrors as $message): ?>
											<div class="help-block">
												<?php echo $message ?>
											</div>
										<?php endforeach ?>
									<?php endforeach ?>
								<?php endif ?>
							<?php endif ?>
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
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;
