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
?>

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
		<?php /* アバター */ ?>
		<?php echo $this->Html->image('/content_comments/img/avatar.png', array(
			'alt' => $video['userAttributesUser']['value'],
			'width' => '60',
			'height' => '60',
		)); ?>
	</div>
	<div class="col-xs-10">
		<div class="form-group" ng-class="workflow.input.class()">
			<div class="input textarea">
				<?php echo $this->Form->textarea(
					'Comment.comment',
					array(
						'class' => 'form-control nc-noresize',
						'rows' => 2,
					)) ?>
			</div>
		</div>

		<div class="has-error">
			<?php if ($this->validationErrors['Comment']): ?>
			<?php foreach ($this->validationErrors['Comment']['comment'] as $message): ?>
				<div class="help-block">
					<?php echo $message ?>
				</div>
			<?php endforeach ?>
			<?php endif ?>
		</div>

		<div class="row">
			<div class="col-xs-12 text-center">
				<a href="<?php echo $this->Html->url('#'); ?>" class="btn btn-success btn-sm">
					<?php echo __d('content_comments', 'コメントする') ?>
				</a>
			</div>
		</div>
	</div>
</div>
