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
?>

<?php if ($contentComments): ?>
<div class="panel panel-default">
	<div class="panel-body workflow-comments">
		<?php foreach ($contentComments as $i => $contentComment): ?>
		<div class="comment form-group <?php echo $i >= ContentCommentsController::START_LIMIT ? 'hidden' : '' ?>">
			<div>
				<a href="" ng-click="user.showUser(<?php echo $contentComment['trackableCreator']['id'] ?>)">
					<b><?php echo $contentComment['trackableCreator']['username'] ?></b>
				</a>
				<small class="text-muted"><?php echo $contentComment['comment']['created'] ?></small>
			</div>
			<div>
				<?php echo nl2br($contentComment['comment']['comment']) ?>
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
</div>
<?php endif ?>
