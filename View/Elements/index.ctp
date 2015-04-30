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
?>

<?php if ($contentComments): ?>
	<div class="content-comments">
		<?php foreach ($contentComments as $i => $contentComment): ?>
		<div class="comment form-group <?php echo $i >= ContentCommentsController::START_LIMIT ? 'hidden' : '' ?>">
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
					<div>
						<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
							<b><?php echo $contentComment['trackableCreator']['username'] ?></b>
						</a>
						<small class="text-muted"><?php echo $contentComment['contentComment']['created'] ?></small>
					</div>
					<div>
						<?php echo nl2br($contentComment['contentComment']['comment']) ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach ?>

		<div class="form-group">
			<button type="button" class="btn btn-info btn-block more <?php echo $i < ContentCommentsController::START_LIMIT ? 'hidden' : '' ?>"
					ng-click="workflow.more()">
				<?php echo h(__d('net_commons', 'More')); ?>
			</button>
		</div>
	</div>
<?php endif ?>
&nbsp;
