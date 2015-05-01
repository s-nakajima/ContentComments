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

App::uses('ContentCommentsController', 'ContentComments.Controller');
echo $this->Html->css('/content_comments/css/style.css', false);
echo $this->Html->script('/content_comments/js/content_comments.js', false);
?>

<?php if ($contentComments): ?>
	<div id="nc-content-comments-<?php echo (int)$frameId; ?>" ng-controller="ContentComments">
		<div class="content-comments">
			<?php foreach ($contentComments as $i => $contentComment): ?>
			<div class="comment <?php echo $i >= ContentCommentsController::START_LIMIT ? 'hidden' : '' ?>">
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
						<div>
							<?php echo nl2br($contentComment['contentComment']['comment']) ?>
						</div>
					</div>
				</div>
				<div class="text-right">
					<?php if ($contentEditable): ?>
						<?php echo $this->Form->button(
							"<span class='glyphicon glyphicon-ok'></span>",
							array(
								'class' => 'btn btn-warning btn-sm',
								'name' => 'add',
						)); ?>
					<?php endif; ?>
					<?php echo $this->Form->button(
						"<span class='glyphicon glyphicon-edit'></span>",
						array(
							'class' => 'btn btn-primary btn-sm',
							'name' => 'add',
						)); ?>
					<?php echo $this->Form->button(
						"<span class='glyphicon glyphicon-trash'></span>",
						array(
							'class' => 'btn btn-danger btn-sm',
							'name' => 'add',
						)); ?>
				</div>
			</div>
			<?php endforeach ?>

			<div class="comment-more">
				<button type="button" class="btn btn-info btn-block more <?php echo $i < ContentCommentsController::START_LIMIT ? 'hidden' : '' ?>"
						ng-click="more()">
					<?php echo h(__d('net_commons', 'More')); ?>
				</button>
			</div>
		</div>
	</div>
<?php endif;