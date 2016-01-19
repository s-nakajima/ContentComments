<?php
/**
 * ContentCommentsComponent 例外エラー Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ContentCommentsComponentAppTest', 'ContentComments.Test/Case/Controller/Component/ContentCommentsComponent');

/**
 * ContentCommentsComponent 例外エラー Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller\Component\ContentCommentsComponent
 */
class ContentCommentsComponentExceptionTest extends ContentCommentsComponentAppTest {

/**
 * コメントする 削除例外テスト
 *
 * @return void
 */
	public function testCommentDeleteException() {
		$this->setExpectedException('InternalErrorException');

		$this->controller->request->data = array(
			'ContentComment' => array(
				'id' => -1,
				'created_user' => '1',
				'plugin_key' => 'plugin_1',
				'content_key' => 'content_1',
				'status' => '1', // 公開
			),
			'_tmp' => array(
				'redirect_url' => 'http://localhost/',
				'process' => ContentCommentsComponent::PROCESS_DELETE, // 削除
			),
			'Block' => array(
				'id' => 'Block_1',
			),
		);

		Current::$current['Permission']['content_comment_publishable']['value'] = '1';
		Current::$current['Permission']['content_comment_editable']['value'] = '1';
		Current::$current['Permission']['content_comment_creatable']['value'] = '1';

		$this->contentComments->initialize($this->controller);

		var_dump($this->contentComments->comment());
	}
}
