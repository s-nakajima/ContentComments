<?php
/**
 * ContentCommentsComponent Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentsComponentAppTest', 'ContentComments.Test/Case/Controller/Component');

/**
 * ContentCommentsComponent Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentTest extends ContentCommentsComponentAppTest {

/**
 * testInitialize method
 *
 * @return void
 */
	public function testInitialize() {
		$this->contentComments->initialize($this->controller);
	}

/**
 * コメントの処理名をパースして取得 通常テスト
 *
 * @return void
 */
	public function testParseProcess() {
		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_ADD => '' // 登録
		);
		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__parseProcess');
		$privateMethod->setAccessible(true);
		//$process = $this->contentComments->__parseProcess();
		$process = $privateMethod->invoke($this->contentComments);

		$this->assertEquals(ContentCommentsComponent::PROCESS_ADD, $process);
	}

	///**
	// * コメントの処理名をパースして取得 Ajaxテスト 未対応(;'∀')
	// *
	// * @return void
	// */
	//	public function testParseProcessAjax() {
	//		$this->controller->data = array(
	//			'process_' . ContentCommentsComponent::PROCESS_ADD => '' // 登録
	//		);
	//		//$this->controller->request->accepts('ajax');
	//		$this->contentComments->initialize($this->controller);
	//		$process = $this->contentComments->parseProcess();
	//
	//		$this->assertEquals(ContentCommentsComponent::PROCESS_ADD, $process);
	//	}

/**
 * コメントの処理名をパースして取得 例外テスト
 *
 * @return void
 */
	public function testParseProcessException() {
		$this->setExpectedException('BadRequestException');

		$this->contentComments->initialize($this->controller);

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__parseProcess');
		$privateMethod->setAccessible(true);
		//$process = $this->contentComments->__parseProcess();
		$privateMethod->invoke($this->contentComments);
	}

/**
 * dataの準備 登録時テスト
 *
 * @return void
 */
	public function testReadyDataAdd() {
		$this->controller->data = array(
			'contentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
		);

		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_ADD; // 登録
		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__readyData');
		$privateMethod->setAccessible(true);
		//$data = $this->contentComments->__readyData();
		$data = $privateMethod->invoke($this->contentComments, $process, $pluginKey, $contentKey);

		$this->assertCount(1, $data);
	}

/**
 * dataの準備 編集時テスト
 *
 * @return void
 */
	public function testReadyDataEdit() {
		$this->controller->data = array(
			'contentComment' => array(
				'id' => 1,
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
		);

		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_EDIT; // 編集
		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__readyData');
		$privateMethod->setAccessible(true);
		//$data = $this->contentComments->__readyData();
		$data = $privateMethod->invoke($this->contentComments, $process, $pluginKey, $contentKey);

		$this->assertCount(1, $data);
	}

/**
 * dataの準備 承認時テスト
 *
 * @return void
 */
	public function testReadyDataApproved() {
		$this->controller->data = array(
			'contentComment' => array(
				'id' => 1,
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			)
		);
		$this->controller->viewVars = array(
			'blockKey' => 'block_1',
		);

		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_APPROVED; // 承認
		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__readyData');
		$privateMethod->setAccessible(true);
		//$data = $this->contentComments->__readyData();
		$data = $privateMethod->invoke($this->contentComments, $process, $pluginKey, $contentKey);

		$this->assertCount(1, $data);
	}

/**
 * dataの準備 対象処理なしテスト
 *
 * @return void
 */
	public function testReadyDataEtc() {
		$this->contentComments->initialize($this->controller);

		$process = ContentCommentsComponent::PROCESS_DELETE; // 削除は対応してない
		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__readyData');
		$privateMethod->setAccessible(true);
		//$data = $this->contentComments->__readyData();
		$data = $privateMethod->invoke($this->contentComments, $process, $pluginKey, $contentKey);

		$this->assertNull($data);
	}
}
