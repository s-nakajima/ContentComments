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

App::uses('ContentCommentsComponentAllTestBase', 'ContentComments.Test/Case/Controller/Component');

/**
 * ContentCommentsComponent Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller\Component\ContentCommentsComponent
 */
class ContentCommentsComponentTest extends ContentCommentsComponentAllTestBase {

/**
 * testInitialize method
 *
 * @return void
 */
	public function testInitialize() {
		$this->contentComments->initialize($this->controller);
	}

/**
 * dataの準備テスト
 *
 * @return void
 */
	public function testReadyData() {
		$this->controller->request->data = array(
			'ContentComment' => array(
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1',
			),
			'_tmp' => array(
				'process' => ContentCommentsComponent::PROCESS_ADD, // 登録
			),
		);

		$this->contentComments->initialize($this->controller);

		Current::$current['Block']['key'] = 'block_1';

		// privateメソッド呼び出し
		$privateMethod = new ReflectionMethod($this->contentComments, '__readyData');
		$privateMethod->setAccessible(true);
		$data = $privateMethod->invoke($this->contentComments);

		//var_dump($data);
		$this->assertEqual('block_1', $data['ContentComment']['block_key']);
	}
}
