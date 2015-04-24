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
 * ContentComments CakeMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Config\Migration
 */
class ContentComments extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'content_comments';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'content_comments' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'block_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'block key | ブロックKey | blocks.key | ', 'charset' => 'utf8'),
					'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'plugin key | プラグインKey | plugins.key | ', 'charset' => 'utf8'),
					'content_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'content key | 各プラグインのコンテンツKey | | ', 'charset' => 'utf8'),
					'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'comment' => 'public status, 1: public, 2: unapproved | 公開状況 1:公開中、2:未承認 | | '),
					'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Comment | コメント | | ', 'charset' => 'utf8'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'blocks_id' => array('type' => 'integer', 'null' => false, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'fk_comments_plugins1_idx' => array('column' => 'plugin_key', 'unique' => 0),
						'fk_content_comments_blocks1_idx' => array('column' => 'block_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'content_comments',
			),
		),
	);

/**
 * recodes
 *
 * @var array $migration
 */
	public $records = array(
		'Plugin' => array(
			array(
				'language_id' => 2,
				'key' => 'content_comments',
				'namespace' => 'netcommons/content_comments',
				'name' => 'コンテンツコメント',
				'type' => 1,
			),
		),

		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'content_comments'
			),
		),

		'PluginsRoom' => array(
			array(
				'room_id' => '1',
				'plugin_key' => 'content_comments'
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
		if ($direction === 'down') {
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}

		return true;
	}

/**
 * Update model records
 *
 * @param string $model model name to update
 * @param string $records records to be stored
 * @param string $scope ?
 * @return bool Should process continue
 */
	public function updateRecords($model, $records, $scope = null) {
		$Model = $this->generateModel($model);
		foreach ($records as $record) {
			$Model->create();
			if (!$Model->save($record, false)) {
				return false;
			}
		}

		return true;
	}
}
