<?php
/**
 * ContentComment validateエラー Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentComment validateエラー Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\ContentComment
 */
class ContentCommentValidateErrorTest extends NetCommonsModelTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'contentComments';

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ContentComment = ClassRegistry::init('ContentComments.ContentComment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContentComment);
		parent::tearDown();
	}

/**
 * debug用 全validateエラーを取得
 *
 * @return void
 */
	public function testSaveContentCommentValidateDebug() {
		//		$data = array('ContentComment' => array(
		//			'block_key' => 'block_1',
		//			'plugin_key' => 'plugin_1',
		//			'content_key' => 'content_1',
		//			'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
		//			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		//		));
		$data = array('ContentComment' => array(
			'status' => 'hoge',
			'comment' => null,
		));
		// コンテンツコメントのデータ保存
		if (!$this->ContentComment->saveContentComment($data)) {
			//var_dump($this->ContentComment->validationErrors);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}

/**
 * block_keyなしエラーテスト
 *
 * @return void
 */
	public function testSaveContentCommentValidateNobBlockKey() {
		$data = array('ContentComment' => array(
			'block_key' => null,
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		if (!$this->ContentComment->saveContentComment($data)) {
			//入力値が不正です
			$this->assertInternalType('string', $this->ContentComment->validationErrors['block_key'][0]);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}

/**
 * plugin_keyなしエラーテスト
 *
 * @return void
 */
	public function testSaveContentCommentValidateNobPluginKey() {
		$data = array('ContentComment' => array(
			'block_key' => 'block_1',
			'plugin_key' => null,
			'content_key' => 'content_1',
			'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		if (!$this->ContentComment->saveContentComment($data)) {
			//入力値が不正です
			$this->assertInternalType('string', $this->ContentComment->validationErrors['plugin_key'][0]);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}

/**
 * content_keyなしエラーテスト
 *
 * @return void
 */
	public function testSaveContentCommentValidateNobContentKey() {
		$data = array('ContentComment' => array(
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => null,
			'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		if (!$this->ContentComment->saveContentComment($data)) {
			//入力値が不正です
			$this->assertInternalType('string', $this->ContentComment->validationErrors['content_key'][0]);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}

/**
 * 公開状況 数字以外エラーテスト
 *
 * @return void
 */
	public function testSaveContentCommentValidateNoNumericStatus() {
		$data = array('ContentComment' => array(
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => 'hoge',
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		if (!$this->ContentComment->saveContentComment($data)) {
			//入力値が不正です
			$this->assertInternalType('string', $this->ContentComment->validationErrors['status'][0]);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}

/**
 * コメントなしエラーテスト
 *
 * @return void
 */
	public function testSaveContentCommentValidateNoComment() {
		$data = array('ContentComment' => array(
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => WorkflowComponent::STATUS_PUBLISHED, // 公開
			'comment' => null,
		));
		if (!$this->ContentComment->saveContentComment($data)) {
			//コメントを入力してください
			$this->assertInternalType('string', $this->ContentComment->validationErrors['comment'][0]);
		} else {
			$this->fail('期待通りのバリデートエラーが発生しませんでした。');
		}
	}
}
