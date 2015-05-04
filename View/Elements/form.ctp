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

<div class="content-comments">
	<div class="comment-form">
		<div class="row">
			<div class="col-xs-12">
			<label class="control-label" for="CommentComment">
				<span class="glyphicon glyphicon-comment"></span>
				<?php echo sprintf(__d('content_comments', '%sコメント'), '999') ?>
			</label>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2">
				<?php /* アバター 暫定対応(;'∀') */ ?>
				<?php echo $this->Html->image('/content_comments/img/avatar.png', array(
					'alt' => $video['userAttributesUser']['value'],
					'width' => '60',
					'height' => '60',
				)); ?>
			</div>
			<div class="col-xs-10">
				<?php echo $this->Form->create($formName, array(
					'name' => 'form',
				)); ?>
					<div class="form-group">
						<div class="input textarea">
							<?php echo $this->Form->textarea(
								'contentComment.comment',
								array(
									'class' => 'form-control nc-noresize',
									'rows' => 2,
							)); ?>
						</div>
					</div>

					<div class="has-error">
						<?php if ($this->validationErrors['contentComment']): ?>
							<?php foreach ($this->validationErrors['contentComment'] as $validationErrors): ?>
								<?php foreach ($validationErrors as $message): ?>
									<div class="help-block">
										<?php echo $message ?>
									</div>
								<?php endforeach ?>
							<?php endforeach ?>
						<?php endif ?>
					</div>

					<div class="row">
						<div class="col-xs-12 text-center">
							<?php echo $this->Form->button(
								__d('content_comments', 'コメントする'),
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
