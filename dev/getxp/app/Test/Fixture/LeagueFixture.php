<?php
/* League Fixture generated on: 2011-12-09 15:37:08 : 1323463028 */

/**
 * LeagueFixture
 *
 */
class LeagueFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => 'hosts profile id'),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => 'venue center id'),
		'network_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'max_competitors' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'format' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'match_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'championship' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'registration' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'start' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'end' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'fee' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'age_min' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'age_max' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 55, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
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
			'user_id' => 1,
			'center_id' => 1,
			'network_id' => 1,
			'max_competitors' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'format' => 'Lorem ipsum dolor sit amet',
			'match_type' => 'Lorem ipsum dolor sit amet',
			'championship' => 'Lorem ipsum dolor sit amet',
			'registration' => 'Lorem ipsum d',
			'start' => '2011-12-09 15:37:08',
			'end' => '2011-12-09 15:37:08',
			'fee' => 'Lorem ipsum dolor sit amet',
			'age_min' => 1,
			'age_max' => 1,
			'name' => 'Lorem ipsum dolor sit amet'
		),
	);
}
