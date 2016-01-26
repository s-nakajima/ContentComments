<?php
/**
 * コンテンツコメント編集 template
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
<?php /* 編集フォーム 非表示 */ ?>
<div ng-show="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>">
	<?php echo $this->NetCommonsForm->create('ContentComment', array(
		'name' => 'form',
		'url' => '/content_comments/content_comments/edit' . Current::read('Frame.id'),
		'type' => 'put',
	)); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.id', array('value' => $contentComment['ContentComment']['id'])); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.created_user', array('value' => $contentComment['ContentComment']['created_user'])); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.plugin_key', array('value' => $pluginKey)); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.content_key', array('value' => $contentKey)); ?>
		<?php echo $this->NetCommonsForm->hidden('_tmp.redirect_url', array('value' => $redirectUrl)); ?>
		<?php echo $this->NetCommonsForm->hidden('_tmp.process', array('value' => ContentCommentsComponent::PROCESS_EDIT)); ?>
		<?php echo $this->NetCommonsForm->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>

		<div class="form-group">
			<div class="input textarea">
				<?php
				$contentCommentComment = array(
					'class' => 'form-control nc-noresize',
					'rows' => 2,
					'value' => h(nl2br($contentComment['ContentComment']['comment'])),
				);

				/* 編集時入力エラー対応 編集処理で、idが同じのみvalueをセットしない */
				if ($this->request->data('_tmp.process') == ContentCommentsComponent::PROCESS_EDIT &&
					$this->request->data('_tmp.ContentComment.id') == $contentComment['ContentComment']['id']) {
					$contentCommentComment['value'] = '';
				}

				echo $this->NetCommonsForm->textarea('ContentComment.comment', $contentCommentComment);
				?>
			</div>
		</div>

		<?php /* 編集時入力エラー対応 編集処理で、idが同じのみエラー表示エリア配置 */ ?>
		<?php if ($this->request->data('_tmp.process') == ContentCommentsComponent::PROCESS_EDIT &&
			$this->request->data('_tmp.ContentComment.id') == $contentComment['ContentComment']['id']): ?>
			<div class="has-error">
				<?php echo $this->NetCommonsForm->error('ContentComment.comment', null, array('class' => 'help-block')); ?>
			</div>
		<?php endif ?>

		<div class="row">
			<div class="col-xs-12 text-center">
				<button type="button" class="btn btn-default btn-sm" ng-click="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?> = false;">
					<?php echo __d('net_commons', 'Cancel') ?>
				</button>
				<?php echo $this->NetCommonsForm->button(
					__d('content_comments', 'Comment'),
					array(
						'class' => 'btn btn-success btn-sm',
				)); ?>
			</div>
		</div>
	<?php echo $this->NetCommonsForm->end(); ?>
</div>

