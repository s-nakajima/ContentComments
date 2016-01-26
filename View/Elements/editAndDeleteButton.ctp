<?php
/**
 * コンテンツコメント 編集・削除ボタン template
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
<?php /* --- 編集ボタン */ ?>
<?php /* 編集の表示・非表示フラグ 非表示 */ ?>
<input class="hide" type="checkbox" ng-model="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>"
	<?php /* 編集時入力エラー対応　編集処理で、idが同じなら編集画面を開く */ ?>
	<?php if ($this->request->data('_tmp.ContentComment.id') == $contentComment['ContentComment']['id']): ?>
		ng-init="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?> = true;"
	<?php endif; ?>>
<button type="button" class="btn btn-primary btn-sm" ng-click="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?> = true;">
	<span class='glyphicon glyphicon-edit'></span>
</button>

<?php /* --- 削除ボタン */ ?>
<?php echo $this->NetCommonsForm->create('ContentComment', array(
	'name' => 'form',
	'style' => 'display: inline;',
	'url' => '/content_comments/content_comments/delete' . Current::read('Frame.id'),
	'type' => 'delete',
)); ?>
	<?php echo $this->NetCommonsForm->hidden('ContentComment.id', array('value' => $contentComment['ContentComment']['id'])); ?>
	<?php echo $this->NetCommonsForm->hidden('ContentComment.created_user', array('value' => $contentComment['ContentComment']['created_user'])); ?>
	<?php echo $this->NetCommonsForm->hidden('ContentComment.plugin_key', array('value' => $pluginKey)); ?>
	<?php echo $this->NetCommonsForm->hidden('ContentComment.content_key', array('value' => $contentKey)); ?>
	<?php echo $this->NetCommonsForm->hidden('_tmp.redirect_url', array('value' => $redirectUrl)); ?>
	<?php echo $this->NetCommonsForm->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>

	<?php echo $this->NetCommonsForm->button(
		"<span class='glyphicon glyphicon-trash'></span>",
		array(
			'class' => 'btn btn-danger btn-sm',
			'onclick' => 'return confirm(\'' . sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('content_comments', 'comment')) . '\')'
	)); ?>
<?php echo $this->NetCommonsForm->end();
