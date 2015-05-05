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

App::uses('ContentComment', 'ContentComments.Model');
App::uses('ContentCommentsComponentAppTest', 'ContentComments.Test/Case/Controller/Component');

/**
 * ContentCommentsComponent 例外エラー Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\NetCommons\Test\Case\Controller
 */
class ContentCommentsComponentExceptionTest extends ContentCommentsComponentAppTest {

/**
 * コメントする 削除例外テスト
 *
 * @return void
 */
	public function testCommentDeleteException() {
		$this->setExpectedException('InternalErrorException');

		$this->controller->data = array(
			'process_' . ContentCommentsComponent::PROCESS_DELETE => '', // 削除
			'contentComment' => array(
				'id' => -1,
			)
		);
		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';

		$this->contentComments->comment($pluginKey, $contentKey);
	}
}
