<?php
/**
 * Fake Model Fixture
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for Fake Model Fixture
 */
class FakeModelFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'KEY |  |  | ', 'charset' => 'utf8'),
		'origin_id' => array('type' => 'integer'),
		'block_id' => array('type' => 'integer'),
		'is_active' => array('type' => 'integer'),
		'is_latest' => array('type' => 'integer'),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'key' => 'content_1',
			'origin_id' => 1,
			'block_id' => 1,
			'is_active' => 1,
			'is_latest' => 0,
			'name' => 'Lorem ipsum dolor sit amet',
		),
		array(
			'id' => 2,
			'key' => 'content_2',
			'origin_id' => 1,
			'block_id' => 1,
			'is_active' => 0,
			'is_latest' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
		),
		array(
			'id' => 3,
			'key' => 'content_3',
			'origin_id' => 1,
			'block_id' => 1,
			'is_active' => 0,
			'is_latest' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
		),
	);

}
