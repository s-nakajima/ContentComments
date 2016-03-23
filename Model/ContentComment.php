<?php
/**
 * コンテンツコメント Model
 *
 * @property Blocks $Blocks
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsAppModel', 'ContentComments.Model');
App::uses('MailQueueBehavior', 'Mails.Model/Behavior');

/**
 * Summary for ContentComment Model
 */
class ContentComment extends ContentCommentsAppModel {

/**
 * use behaviors
 *
 * @var array
 * @see MailQueueBehavior
 */
	public $actsAs = array(
		'Mails.MailQueue' => array(		// 自動でメールキューの登録, 削除
			'embedTags' => array(
				'X-SUBJECT' => '_mail.content_title',
				'X-BODY' => 'ContentComment.comment',
				'X-URL' => '_mail.url',
			),
			'useCommentApproval' => '_mail.use_comment_approval',
			'workflowType' => MailQueueBehavior::MAIL_QUEUE_WORKFLOW_TYPE_COMMENT,
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'block_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'plugin_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'content_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'status' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'comment' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('content_comments', 'comment')),
				)
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Get conditions
 *
 * @param string|array $contentKeys コンテンツキー
 * @return query conditions
 */
	public function getConditions($contentKeys) {
		$conditions = array(
			'block_key' => Current::read('Block.key'),
			'plugin_key' => Current::read('Plugin.key'),
			'content_key' => $contentKeys
		);

		// 公開権限あり
		if (Current::permission('content_comment_publishable')) {
			return $conditions;
		}

		// ログインしていない
		if (! (bool)AuthComponent::user()) {
			$conditions['ContentComment.status'] = WorkflowComponent::STATUS_PUBLISHED;
			return $conditions;
		}

		// 公開権限なし、ログイン済み
		$addConditions = array(
			'OR' => array(
				'ContentComment.status' => WorkflowComponent::STATUS_PUBLISHED,
				'ContentComment.created_user' => (int)AuthComponent::user('id'),
			)
		);
		$conditions = array_merge($conditions, $addConditions);

		return $conditions;
	}

/**
 * コンテンツコメント データ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveContentComment($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			if (! $contentComment = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $contentComment;
	}

/**
 * コンテンツコメント データ削除
 *
 * @param int $id ID
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteContentComment($id) {
		if (empty($id)) {
			return false;
		}

		$this->Behaviors->unload('Mails.MailQueue');

		//トランザクションBegin
		$this->begin();

		try {
			if (! $this->delete($id, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}
}
