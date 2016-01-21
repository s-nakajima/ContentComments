<?php
/**
 * ContentCommentHelper Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('View', 'View');
App::uses('ContentCommentHelper', 'ContentComments.View/Helper');
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('WorkflowComponent', 'Workflow.Controller/Component');

/**
 * ContentCommentHelper Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Helper\ContentComment
 */
class ContentCommentHelperCountTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
//		'plugin.likes.like',
//		'plugin.likes.likes_user',
		'plugin.content_comments.content_comment',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->ContentComment = new LikeHelper($View);
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
 * buttonsのテスト
 *
 * @param array $model String of model name
 * @param array $setting Array of use like setting data.
 * @param array $content Array of content data with like count.
 * @dataProvider dataProviderButtons
 * @return void
 */
	public function testButtons($model, $setting, $content) {
		$result = $this->ContentComment->count('Content', $setting, $content);

		if ($content['Content']['status'] === WorkflowComponent::STATUS_PUBLISHED && $setting['use_like'] === 1) {
			$this->assertContains('<button name="save" class="btn btn-link btn-likes"', $result);
		} else {
			$this->assertNotContains('<button name="save" class="btn btn-link btn-likes"', $result);
		}
		if ($setting['use_like'] === 1) {
			$this->assertContains('glyphicon glyphicon-thumbs-up', $result);
		} else {
			$this->assertNotContains('glyphicon glyphicon-thumbs-up', $result);
		}

		if ($setting['use_unlike'] === 1) {
			$this->assertContains('glyphicon glyphicon-thumbs-down', $result);
		} else {
			$this->assertNotContains('glyphicon glyphicon-thumbs-down', $result);
		}
	}

/**
 * buttonsのDataProvider
 *
 * #### 戻り値
 *  - model モデル名
 *  - setting like setting data
 *  - content like content data
 *
 * @return array
 */
	public function dataProviderButtons() {
		$model = 'Content';
		$setting1 = array('use_like' => 1, 'use_unlike' => 1);
		$content1 = array('Content' => array('key' => 'content_key', 'status' => '1'));

		$setting2 = array('use_like' => 1, 'use_unlike' => 0);
		$content2 = array('Content' => array('key' => 'content_key', 'status' => '1'));

		$setting3 = array('use_like' => 0, 'use_unlike' => 0);
		$content3 = array('Content' => array('key' => 'content_key', 'status' => '1'));

		$setting4 = array('use_like' => 1, 'use_unlike' => 1);
		$content4 = array('Content' => array('key' => 'content_key', 'status' => '2'));

		return array(
			array($model, $setting1, $content1),
			array($model, $setting2, $content2),
			array($model, $setting3, $content3),
			array($model, $setting4, $content4),
		);
	}

}
