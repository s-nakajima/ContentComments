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
 * @param string $contentKey コンテンツキー
 * @param array $contentComment コンテンツコメント一覧の1件データ
 * @param string $contentTitleForMail メールのためのコンテンツタイトル
 * @param bool $useCommentApproval コンテントコメント承認利用フラグ
 */
$this->NetCommonsHtml->css(array('/content_comments/css/style.css'));
?>
<?php /* 編集フォーム 非表示 */ ?>
<div ng-show="isDisplayEdit<?php echo $contentComment['ContentComment']['id']; ?>">
	<?php echo $this->NetCommonsForm->create('ContentComment', array(
		'name' => 'form',
		'url' => NetCommonsUrl::blockUrl(['plugin' => 'content_comments', 'controller' => 'content_comments', 'action' => 'edit']),
		'type' => 'put',
	)); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.id', array('value' => $contentComment['ContentComment']['id'])); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.created_user', array('value' => $contentComment['ContentComment']['created_user'])); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.plugin_key', array('value' => $pluginKey)); ?>
		<?php echo $this->NetCommonsForm->hidden('ContentComment.content_key', array('value' => $contentKey)); ?>
		<?php echo $this->NetCommonsForm->hidden('Block.id', array('value' => Current::read('Block.id'))); ?>
		<?php echo $this->NetCommonsForm->hidden('_mail.content_title', array('value' => $contentTitleForMail)); ?>
		<?php echo $this->NetCommonsForm->hidden('_mail.use_comment_approval', array('value' => $useCommentApproval)); ?>
		<?php
		// コメント承認ありで、公開権限なしの人が公開記事を更新したら、未承認にする
		if ($useCommentApproval &&
			!Current::permission('content_comment_publishable') &&
			$contentComment['ContentComment']['status'] == WorkflowComponent::STATUS_PUBLISHED) {

			echo $this->NetCommonsForm->hidden('ContentComment.status', array('value' => WorkflowComponent::STATUS_APPROVAL_WAITING));
		}
		?>

		<div class="form-group">
			<?php
			$err = Hash::get($this->validationErrors, 'ContentComment.comment');
			$isEdit = $this->Session->read('ContentComments.forRedirect.requestData.id') == $contentComment['ContentComment']['id'];
			$hasError = '';
			// 編集で、エラーメッセージあり
			if ($isEdit && !empty($err)) {
				$hasError = 'has-error';
			}
			?>
			<div class="<?php echo $hasError; ?>">
				<?php
				$contentCommentComment = array(
					'class' => 'form-control nc-noresize',
					'rows' => 2,
					'value' => $contentComment['ContentComment']['comment'],
				);
				// 編集時入力エラー対応 編集処理で、idが同じのみSessionのvalueをセット
				if ($isEdit) {
					$contentCommentComment['value'] = $this->Session->read('ContentComments.forRedirect.requestData.comment');
				}
				echo $this->NetCommonsForm->textarea('ContentComment.comment', $contentCommentComment);

				// 編集時入力エラー対応 編集処理で、idが同じのみエラー表示エリア配置
				if ($isEdit) {
					echo $this->NetCommonsForm->error('ContentComment.comment');
				}
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 text-center">
				<?php echo $this->Button->cancelAndSave(
					__d('net_commons', 'Cancel'),
					__d('net_commons', 'OK'),
					'#',
					array(
						'class' => 'btn btn-default btn-sm btn-workflow',
						'ng-click' => 'isDisplayEdit' . $contentComment['ContentComment']['id'] . ' = false;',
					),
					array(
						'class' => 'btn btn-primary btn-sm  btn-workflow'
					)
				); ?>
			</div>
		</div>
	<?php echo $this->NetCommonsForm->end(); ?>
</div>

