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
App::uses('ContentComment', 'ContentComments.Model');

App::uses('ComponentCollection', 'Controller');
App::uses('SessionComponent', 'Controller/Component');

/**
 * ContentComments Component
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Controller\Component
 */
class ContentCommentsComponent extends Component {

/**
 * @var SessionComponent
 */
	public $Session = null;

/**
 * @var Controller
 */
	protected $_controller = null;

/**
 * @var int start limit
 */
	const START_LIMIT = 5;

/**
 * @var int max limit
 */
	const MAX_LIMIT = 100;

/**
 * @var string 登録処理
 */
	const PROCESS_ADD = '1';

/**
 * @var string 編集処理
 */
	const PROCESS_EDIT = '2';

/**
 * @var string 削除処理
 */
	const PROCESS_DELETE = '3';

/**
 * @var string 承認処理
 */
	const PROCESS_APPROVED = '4';

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Instantiating controller
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->_controller = $controller;
	}

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::startup
 */
	public function startup(Controller $controller) {

		// コンポーネントから他のコンポーネントを使用する
		$collection = new ComponentCollection();
		$this->Session = new SessionComponent($collection);

		// コンテントコメントからエラーメッセージを受け取る仕組み http://skgckj.hateblo.jp/entry/2014/02/09/005111
		if ($this->Session->read('errors')) {
			foreach ($this->Session->read('errors') as $model => $errors) {
				$this->_controller->$model->validationErrors = $errors;
			}
			// 表示は遷移・リロードまでの1回っきりなので消す
			$this->Session->delete('errors');
		}
	}

/**
 * Called before the Controller::beforeRender(), and before
 * the view class is loaded, and before Controller::render()
 *
 * @param Controller $controller Controller with components to beforeRender
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers/components.html#Component::beforeRender
 */
	public function beforeRender(Controller $controller) {
//		// コメントを利用する
//		if ($videoBlockSetting['VideoBlockSetting']['use_comment']) {
//			// コンテンツコメントの取得
//			$contentComments = $this->_controller->ContentComment->getContentComments(array(
//				'block_key' => Current::read('Block.key'),
//				'plugin_key' => $this->request->params['plugin'],
//				'content_key' => $video['Video']['key'],
//			));
//
//			$this->_controller->request->data['ContentComments'] = $contentComments;
//		}
	}

/**
 * コメントする
 *
 * @return bool 成功 or 失敗
 */
	public function comment() {
		$process = $this->_controller->request->data('_tmp.process');

		// パーミッションがあるかチェック
		if (!$this->__checkPermission($process)) {
			return false;
		}

		// 登録・編集・承認
		if ($process == $this::PROCESS_ADD ||
			$process == $this::PROCESS_EDIT ||
			$process == $this::PROCESS_APPROVED) {

			// dataの準備
			$data = $this->__readyData();

//$this->log($this->controller->request->data, 'debug');
			// コンテンツコメントのデータ保存
			if (!$this->_controller->ContentComment->saveContentComment($data)) {
//$this->log($this->controller->ContentComment->validationErrors, 'debug');
				$this->_controller->NetCommons->handleValidationError($this->_controller->ContentComment->validationErrors);
//$this->log($this->controller->validationErrors, 'debug');
				// 別プラグインにエラーメッセージを送るため  http://skgckj.hateblo.jp/entry/2014/02/09/005111
				$this->_controller->Session->write('errors.ContentComment', $this->_controller->ContentComment->validationErrors);

				// 正常
			} else {
				// 下記は悪さをしないため、if文 で分岐しない
				// 登録用：入力欄のコメントを空にする
				unset($this->_controller->request->data['ContentComment']['comment']);

				// 編集用：編集処理を取り除く（編集後は、対象コメントの入力欄を開けないため）
				unset($this->_controller->request->data['process_' . ContentCommentsComponent::PROCESS_EDIT]);
			}

			// 削除
		} elseif ($process == $this::PROCESS_DELETE) {
			// コンテンツコメントの削除
			if (!$this->_controller->ContentComment->deleteContentComment($this->_controller->request->data('ContentComment.id'))) {
				return false;
			}
		}
		return true;
	}

/**
 * パーミッションがあるかチェック
 *
 * @param int $process どの処理
 * @return bool true:パーミッションあり or false:パーミッションなし
 */
	private function __checkPermission($process) {
		// 登録処理 and 投稿許可あり
		if ($process == $this::PROCESS_ADD && Current::permission('content_comment_creatable')) {
			return true;

			// (編集処理 or 削除処理) and (編集許可あり or 自分で投稿したコメントなら、編集・削除可能)
		} elseif (($process == $this::PROCESS_EDIT || $process == $this::PROCESS_DELETE) && (
				Current::permission('content_comment_editable') ||
				$this->_controller->data['ContentComment']['created_user'] == (int)AuthComponent::user('id')
		)) {
			return true;

			// 承認処理 and 承認許可あり
		} elseif ($process == $this::PROCESS_APPROVED && Current::permission('content_comment_publishable')) {
			return true;

		}
		return false;
	}

/**
 * dataの準備
 *
 * @return array data
 */
	private function __readyData() {
		$data['ContentComment'] = $this->_controller->request->data('ContentComment');
		$data['ContentComment']['block_key'] = Current::read('Block.key');

		return $data;
	}
}
