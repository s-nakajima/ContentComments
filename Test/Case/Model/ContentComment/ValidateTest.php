<?php
/**
 * ContentComment::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('ContentCommentFixture', 'ContentComments.Test/Fixture');

/**
 * ContentComment::validate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Case\Model\ContentComment
 */
class ContentCommentValidateTest extends NetCommonsValidateTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.content_comments.content_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'content_comments';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'ContentComment';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validates';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 */
	public function dataProviderValidationError() {
		$data['ContentComment'] = (new ContentCommentFixture())->records[0];

		//debug($data);
		return array(
			'block_key:空エラー' => array('data' => $data, 'field' => 'block_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			'plugin_key:空エラー' => array('data' => $data, 'field' => 'plugin_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			'content_key:空エラー' => array('data' => $data, 'field' => 'content_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			'status:数値以外エラー' => array('data' => $data, 'field' => 'status', 'value' => 'xxx',
				'message' => __d('net_commons', 'Invalid request.')),
			'comment:空エラー' => array('data' => $data, 'field' => 'comment', 'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('content_comments', 'comment'))),
		);
	}

}
