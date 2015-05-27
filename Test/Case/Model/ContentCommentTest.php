<?php
/**
 * ContentComment Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentAppTest', 'ContentComments.Test/Case/Model');

/**
 * ContentComment Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class ContentCommentTest extends ContentCommentAppTest {

/**
 * testFindById
 *
 * @return void
 */
	public function testFindById() {
		$id = 1;
		$rtn = $this->ContentComment->findById($id);
		$this->assertTrue(is_array($rtn));
	}

/**
 * コンテンツコメント データ取得テスト 1件あり
 *
 * @return void
 */
	public function testGetContentComments() {
		$contentComments = $this->ContentComment->getContentComments(array(
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
		));
		$this->assertCount(2, $contentComments);
	}

/**
 * コンテンツコメントのデータ保存 登録テスト
 *
 * @return void
 */
	public function testSaveContentCommentAdd() {
		$data = array('ContentComment' => array(
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => ContentComment::STATUS_PUBLISHED, // 公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		$contentComment = $this->ContentComment->saveContentComment($data);
		$this->assertCount(1, $contentComment);
	}

/**
 * コンテンツコメントのデータ保存 編集テスト
 *
 * @return void
 */
	public function testSaveContentCommentEdit() {
		$data = array('ContentComment' => array(
			'id' => 1,
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => ContentComment::STATUS_PUBLISHED, // 公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
		));
		$contentComment = $this->ContentComment->saveContentComment($data);
		$this->assertCount(1, $contentComment);
	}

/**
 * コンテンツコメント データ削除テスト
 *
 * @return void
 */
	public function testDeleteContentComment() {
		$id = 1;
		$this->assertTrue($this->ContentComment->deleteContentComment($id));
	}

/**
 * コンテンツコメント データ削除 IDなしテスト
 *
 * @return void
 */
	public function testDeleteContentCommentNoId() {
		$id = null;
		$this->assertTrue(!$this->ContentComment->deleteContentComment($id));
	}
}
