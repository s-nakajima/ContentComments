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
		$this->controller->viewVars = array(
			'contentCommentPublishable' => true,
			'contentCommentEditable' => true,
			'contentCommentCreatable' => true,
		);

		$this->contentComments->initialize($this->controller);

		$pluginKey = 'plugin_1';
		$contentKey = 'content_1';
		$isCommentApproved = true; // 自動承認する

		$this->contentComments->comment($pluginKey, $contentKey, $isCommentApproved);
	}

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
}
