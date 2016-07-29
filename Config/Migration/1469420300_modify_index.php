<?php
/**
 * Migration file
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * ModifyIndex CakeMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\ContentComments\Config\Migration
 */
class ModifyIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'modify_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'content_comments' => array('indexes' => array('fk_comments_plugins1_idx', 'fk_content_comments_blocks1_idx')),
			),
			'create_field' => array(
				'content_comments' => array(
					'indexes' => array(
						'block_key' => array('column' => array('block_key', 'plugin_key', 'content_key', 'created'), 'unique' => 0, 'length' => array('191', '191', '191')),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'content_comments' => array(
					'indexes' => array(
						'fk_comments_plugins1_idx' => array('column' => 'plugin_key', 'unique' => 0, 'length' => array('191')),
						'fk_content_comments_blocks1_idx' => array('column' => 'block_key', 'unique' => 0, 'length' => array('191')),
					),
				),
			),
			'drop_field' => array(
				'content_comments' => array('indexes' => array('block_key')),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
