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

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * ContentComment Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\ContentComment
 */
class ContentCommentTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
	);

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
 * コンテンツコメントのデータ保存 承認テスト
 * コメントを含まないで登録
 *
 * @return void
 */
	public function testSaveContentCommentApproved() {
		$data = array('ContentComment' => array(
			'id' => 3,
			'block_key' => 'block_2',
			'plugin_key' => 'plugin_2',
			'content_key' => 'content_2',
			'status' => ContentComment::STATUS_PUBLISHED, // 公開
		));
		$contentComment = $this->ContentComment->saveContentComment($data);
		//var_dump($this->ContentComment->validationErrors);
		//var_dump($contentComment);

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
