<?php
/**
 * ContentComment 例外エラー Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * ContentComment 例外 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\ContentComment
 */
class ContentCommentExceptionTest extends NetCommonsModelTestCase {

/**
 * Plugin name
 *
 * @var array
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
 * データ保存 例外テスト
 *
 * @return void
 */
	public function testSaveContentCommentException() {
		$this->setExpectedException('InternalErrorException');

		$data = array(
			'ContentComment' => array(
				'id' => 0,
				'block_key' => 111,
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => ContentComment::STATUS_PUBLISHED, // 公開
				'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			),
		);

		// 例外を発生させるためのモック
		$contentCommentMock = $this->getMockForModel('ContentComments.ContentComment', ['save']);
		$contentCommentMock->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$contentCommentMock->saveContentComment($data);
	}

/**
 * データ削除 例外テスト
 *
 * @return void
 */
	public function testDeleteContentCommentException() {
		$this->setExpectedException('InternalErrorException');

		$id = -1;
		$this->ContentComment->deleteContentComment($id);
	}
}
