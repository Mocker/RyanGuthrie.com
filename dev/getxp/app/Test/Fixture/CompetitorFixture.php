<?php
/* Competitor Fixture generated on: 2011-12-09 18:18:19 : 1323472699 */

/**
 * CompetitorFixture
 *
 */
class CompetitorFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'profile_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'player_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => 'optional name of competitor - can be used for manual entries', 'charset' => 'latin1'),
		'league_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'approved' => array('type' => 'integer', 'null' => false, 'default' => '0', 'collate' => NULL, 'comment' => ''),
		'members' => array('type' => 'integer', 'null' => false, 'default' => '1', 'collate' => NULL, 'comment' => 'number of ppl on team'),
		'profile2_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => '2nd player id for duo\'s'),
		'level' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 25, 'collate' => 'latin1_swedish_ci', 'comment' => 'beginner,advanced etc', 'charset' => 'latin1'),
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
			'profile_id' => 1,
			'player_name' => 'Lorem ipsum dolor sit amet',
			'league_id' => 1,
			'approved' => 1,
			'members' => 1,
			'profile2_id' => 1,
			'level' => 'Lorem ipsum dolor sit a'
		),
	);
}
