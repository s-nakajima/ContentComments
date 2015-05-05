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

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentAppTest', 'ContentComments.Test/Case/Model');

/**
 * ContentComment 例外 Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model
 */
class ContentCommentExceptionTest extends ContentCommentAppTest {

/**
 * block_keyなしエラーテスト 未完成
 *
 * @return void
 */
	public function testSaveContentCommentException() {
		//$this->setExpectedException('InternalErrorException');

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
		$this->ContentComment->saveContentComment($data);
		//$rtn = $this->ContentComment->saveContentComment($data);
		//var_dump($rtn);
	}
}
