<?php
/* Center Fixture generated on: 2011-11-30 11:22:27 : 1322670147 */

/**
 * CenterFixture
 *
 */
class CenterFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'state' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'street' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'zip' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'phone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'courts' => array('type' => 'integer', 'null' => true, 'default' => '0', 'collate' => NULL, 'comment' => ''),
		'surfaces' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'features' => array('type' => 'string', 'null' => false, 'default' => '0000000000', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'sport_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'owner' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'city' => 'Lorem ipsum dolor sit amet',
			'state' => 'Lorem ipsum dolor ',
			'street' => 'Lorem ipsum dolor sit amet',
			'zip' => 'Lorem ip',
			'phone' => 'Lorem ipsum d',
			'courts' => 1,
			'surfaces' => 1,
			'features' => 'Lorem ip',
			'sport_id' => 1,
			'owner' => 1
		),
	);
}
