<?php
/**
 * コンテンツコメント一覧の1件表示＆編集 template
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
 * @param string $redirectUrl 操作後の遷移URL
 * @param array $contentComment コンテンツコメント一覧の1件データ
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));
?>
<div class="media">
	<div class="pull-left">
		<?php /* アバター */ ?>
		<?php echo $this->DisplayUser->avatarLink($contentComment, array(
			'class' => '',
		)); ?>
	</div>
	<div class="media-body">
		<div class="row">
			<div class="col-xs-6">
				<?php echo $this->DisplayUser->handleLink($contentComment); ?>

				<?php /* ステータス */ ?>
				<?php echo $this->Workflow->label($contentComment['ContentComment']['status'], array(
					ContentComment::STATUS_APPROVED => array(
						'class' => 'label-warning',
						'message' => __d('content_comments', 'Approving'),
					),
				)); ?>
			</div>
			<div class="col-xs-6 text-right">
				<small class="text-muted"><?php echo $this->Date->dateFormat($contentComment['ContentComment']['created']); ?></small>
			</div>
		</div>

		<?php /* コメント表示 */ ?>
		<div ng-hide="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>">
			<?php echo h(nl2br($contentComment['ContentComment']['comment'])); ?>
		</div>

		<?php /* コンテンツコメント編集許可あり or 自分で投稿したコメントなら、編集可能 */ ?>
		<?php if (Current::permission('content_comment_editable') || $contentComment['ContentComment']['created_user'] == (int)AuthComponent::user('id')): ?>
			<?php /* 編集 */ ?>
			<?php echo $this->element('ContentComments.edit', array(
				'pluginKey' => $pluginKey,
				'contentKey' => $contentKey,
				'redirectUrl' => $redirectUrl,
				'contentComment' => $contentComment,
			)); ?>
		<?php endif; ?>

	</div>
</div>
