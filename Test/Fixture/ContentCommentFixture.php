<?php
/**
 * ContentCommentFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for ContentCommentFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Test\Fixture
 */
class ContentCommentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'),
		'block_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'ブロックKey', 'charset' => 'utf8'),
		'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'プラグインKey', 'charset' => 'utf8'),
		'content_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '各プラグインのコンテンツKey', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'comment' => '公開状況 1:公開中、2:未承認'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'コメント', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_comments_plugins1_idx' => array('column' => 'plugin_key', 'unique' => 0),
			'fk_content_comments_blocks1_idx' => array('column' => 'block_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'block_key' => 'Lorem ipsum dolor sit amet',
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'status' => 1,
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		array(
			'id' => 2,
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => 1, //公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		array(
			'id' => 3,
			'block_key' => 'block_2',
			'plugin_key' => 'plugin_2',
			'content_key' => 'content_2',
			'status' => 2, //未承認
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		array(
			'id' => 4,
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'content_1',
			'status' => 1, //公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		array(
			'id' => 5,
			'block_key' => 'block_2',
			'plugin_key' => 'plugin_2',
			'content_key' => 'content_2',
			'status' => 1, //公開
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		// ContentCommentBehavior用
		array(
			'id' => 6,
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'publish_key',
			'status' => 1,
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
		array(
			'id' => 7,
			'block_key' => 'block_1',
			'plugin_key' => 'plugin_1',
			'content_key' => 'publish_key',
			'status' => 2, //未承認
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => 1,
			'created' => '2015-04-24 16:45:16',
			'modified_user' => 1,
			'modified' => '2015-04-24 16:45:16',
		),
	);

}
