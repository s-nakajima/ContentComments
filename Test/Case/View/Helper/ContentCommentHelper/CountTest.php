<?php
/**
 * ContentCommentHelper::count()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * ContentCommentHelper::count()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\View\Helper\ContentCommentHelper
 */
class ContentCommentHelperCountTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'content_comments';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストデータ生成
		$viewVars = array(
			'fake' => array(
				'Fake' => array(
					'key' => 'content_1',
					'title' => 'テスト件名',
				),
			),
			'fakeSetting' => array(
				'use_comment' => '1',
				'use_comment_approval' => '1',
			),
		);
		$requestData = array();
		$params = array();

		//Helperロード
		$this->loadHelper('ContentComments.ContentComment', $viewVars, $requestData, $params);
		$this->ContentComment->settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'fake.Fake.key',
				'contentTitleForMail' => 'fake.Fake.title',
				'useComment' => 'fakeSetting.use_comment',
				'useCommentApproval' => 'fakeSetting.use_comment_approval',
			),
		);
	}

/**
 * count()のテスト
 *
 * @return void
 */
	public function testCount() {
		//データ生成
		$content = array(
			'ContentCommentCnt' => array(
				'cnt' => 1,
				'approval_cnt' => 1
			),
		);
		$attributes = array();

		//テスト実施
		$result = $this->ContentComment->count($content, $attributes);

		//チェック
		$this->assertContains('1' . sprintf(__d('content_comments', '（%s unapproved）'), 1), $result);
		//var_dump($result);
	}

/**
 * count()のテスト - コメント利用しない
 *
 * @return void
 */
	public function testCountNotUseComment() {
		//テストデータ生成
		$viewVars = array(
			'fake' => array(
				'Fake' => array(
					'key' => 'content_1',
					'title' => 'テスト件名',
				),
			),
			'fakeSetting' => array(
				'use_comment' => '0',
				'use_comment_approval' => '1',
			),
		);
		$requestData = array();
		$params = array();

		//Helperロード
		$this->loadHelper('ContentComments.ContentComment', $viewVars, $requestData, $params);
		$this->ContentComment->settings = array(
			'viewVarsKey' => array(
				'contentKey' => 'fake.Fake.key',
				'contentTitleForMail' => 'fake.Fake.title',
				'useComment' => 'fakeSetting.use_comment',
				'useCommentApproval' => 'fakeSetting.use_comment_approval',
			),
		);

		//データ生成
		$content = null;
		$attributes = array();

		//テスト実施
		$result = $this->ContentComment->count($content, $attributes);

		//チェック
		$this->assertEmpty($result);
		//debug($result);
	}

}
