<?php
/**
 * ContentComments Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * ContentComments Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Controller\Component
 */
class ContentCommentsComponent extends Component {

/**
 * start limit
 *
 * @var int
 */
	const START_LIMIT = 5;

/**
 * max limit
 *
 * @var int
 */
	const MAX_LIMIT = 100;

/**
 * 登録処理
 *
 * @var string
 */
	const PROCESS_ADD = '1';

/**
 * 編集処理
 *
 * @var string
 */
	const PROCESS_EDIT = '2';

/**
 * 削除処理
 *
 * @var string
 */
	const PROCESS_DELETE = '3';

/**
 * 承認処理
 *
 * @var string
 */
	const PROCESS_APPROVED = '4';

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 * コメントする
 *
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param bool $isCommentApproved コメントの自動承認
 * @return bool 成功 or 失敗
 */
	public function comment($pluginKey, $contentKey, $isCommentApproved) {
		// コンテンツコメントの処理名をパースして取得
		if (!$process = $this->__parseProcess()) {
			return false;
		}
		// パーミッションがあるかチェック
		if (!$this->__checkPermission($process)) {
			return false;
		}

		// 登録・編集・承認
		if ($process == $this::PROCESS_ADD ||
			$process == $this::PROCESS_EDIT ||
			$process == $this::PROCESS_APPROVED) {

			// dataの準備
			$data = $this->__readyData($process, $pluginKey, $contentKey, $isCommentApproved);

			// コンテンツコメントのデータ保存
			if (!$this->controller->ContentComment->saveContentComment($data)) {
				if (!$this->controller->handleValidationError($this->controller->ContentComment->validationErrors)) {
					// コンテンツコメント編集表示を開く
					//$results['isDisplayEdit'] = $frameId . '_' . $this->data['contentComment']['id'];
					$this->log($this->controller->validationErrors, 'debug');
				}
			}

			// 削除
		} elseif ($process == $this::PROCESS_DELETE) {
			// コンテンツコメントの削除
			if (!$this->controller->ContentComment->deleteContentComment($this->controller->data['contentComment']['id'])) {
				return false;
			}
		}
		//unset($this->data['ContentComment']['comment']);
		return true;
	}

/**
 * コメントの処理名をパースして取得
 *
 * @throws BadRequestException
 * @return int どの処理
 */
	private function __parseProcess() {
		if ($matches = preg_grep('/^process_\d/', array_keys($this->controller->data))) {
			list(, $process) = explode('_', array_shift($matches));
		} else {
			if ($this->controller->request->is('ajax')) {
				$this->controller->renderJson(
					['error' => ['validationErrors' => ['status' => __d('net_commons', 'Invalid request.')]]],
					__d('net_commons', 'Bad Request'), 400
				);
			} else {
				throw new BadRequestException(__d('net_commons', 'Bad Request'));
			}
			return false;
		}

		return $process;
	}

/**
 * パーミッションがあるかチェック
 *
 * @param int $process どの処理
 * @return bool true:パーミッションあり or false:パーミッションなし
 */
	private function __checkPermission($process) {
		// 登録処理 and 投稿権限あり
		if ($process == $this::PROCESS_ADD && $this->controller->viewVars['contentCommentCreatable']) {
			return true;

			// 編集処理 and (編集権限あり or 自分で投稿したコメントなら、編集・削除可能)
		} elseif ($process == $this::PROCESS_EDIT && (
				$this->controller->viewVars['contentCommentEditable'] ||
				$this->controller->data['contentComment']['createdUser'] == (int)AuthComponent::user('id')
		)) {
			return true;

			// 削除処理 and (編集権限あり or 自分で投稿したコメントなら、編集・削除可能)
		} elseif ($process == $this::PROCESS_DELETE && (
				$this->controller->viewVars['contentCommentEditable'] ||
				$this->controller->data['contentComment']['createdUser'] == (int)AuthComponent::user('id')
			)) {
			return true;

			// 承認処理 and 承認権限あり
		} elseif ($process == $this::PROCESS_APPROVED && $this->controller->viewVars['contentCommentPublishable']) {
			return true;

		}
		return false;
	}

/**
 * dataの準備
 *
 * @param int $process どの処理
 * @param string $pluginKey プラグインキー
 * @param string $contentKey コンテンツキー
 * @param bool $isCommentApproved コメントの自動承認
 * @return array data
 */
	private function __readyData($process, $pluginKey, $contentKey, $isCommentApproved) {
		$data = null;

		// 登録処理
		if ($process == $this::PROCESS_ADD) {
			// 公開 or 未承認
			$status = $isCommentApproved ? ContentComment::STATUS_PUBLISHED : ContentComment::STATUS_APPROVED;

			$data = array('ContentComment' => array(
				'block_key' => $this->controller->viewVars['blockKey'],
				'plugin_key' => $pluginKey,
				'content_key' => $contentKey,
				'status' => $status,
				'comment' => $this->controller->data['contentComment']['comment'],
			));

			// 編集処理
		} elseif ($process == $this::PROCESS_EDIT) {
			$data = array('ContentComment' => array(
				'id' => $this->controller->data['contentComment']['id'],
				'block_key' => $this->controller->viewVars['blockKey'],
				'plugin_key' => $pluginKey,
				'content_key' => $contentKey,
				'comment' => $this->controller->data['contentComment']['comment'],
			));

			// 承認処理
		} elseif ($process == $this::PROCESS_APPROVED) {
			$data = array('ContentComment' => array(
				'id' => $this->controller->data['contentComment']['id'],
				'block_key' => $this->controller->viewVars['blockKey'],
				'plugin_key' => $pluginKey,
				'content_key' => $contentKey,
				'status' => ContentComment::STATUS_PUBLISHED, // 公開
			));
		}

		return $data;
	}
}
