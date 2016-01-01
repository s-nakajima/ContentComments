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

/**
 * Summary for ContentComment Model
 */
class ContentComment extends ContentCommentsAppModel {

/**
 * 公開状況 公開中
 *
 * @var string
 */
	const STATUS_PUBLISHED = '1';

/**
 * 公開状況 未承認
 *
 * @var string
 */
	const STATUS_APPROVED = '2';

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
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
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
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * コンテンツコメント データ取得
 *
 * @param array $conditions conditions
 * @return array
 */
	public function getContentComments($conditions) {
		return $this->find('all', array(
			'conditions' => $conditions,
			'order' => $this->alias . '.created DESC',
		));
	}

/**
 * コンテンツコメント データ保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveContentComment($data) {
		$this->loadModels(array(
			'ContentComment' => 'ContentComments.ContentComment',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			// 値をセット
			$this->set($data);

			// 入力チェック
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}

			$contentComment = $this->save(null, false);
			if (!$contentComment) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
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
		$this->loadModels(array(
			'ContentComment' => 'ContentComments.ContentComment',
		));

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			if (! $this->delete($id, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$dataSource->commit();

		} catch (InternalErrorException $ex) {
			$dataSource->rollback();
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}
		return true;
	}
}
