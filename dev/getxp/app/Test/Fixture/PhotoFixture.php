<?php
/* Photo Fixture generated on: 2012-02-27 16:24:40 : 1330377880 */

/**
 * PhotoFixture
 *
 */
class PhotoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'profile_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'network_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'caption' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'posted' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'collate' => NULL, 'comment' => ''),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 90, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'file' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
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
			'center_id' => 1,
			'user_id' => 1,
			'profile_id' => 1,
			'network_id' => 1,
			'caption' => 'Lorem ipsum dolor sit amet',
			'posted' => 1330377880,
			'url' => 'Lorem ipsum dolor sit amet',
			'file' => 'Lorem ipsum dolor sit amet'
		),
	);
}
