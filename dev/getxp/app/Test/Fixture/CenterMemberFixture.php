<?php
/* CenterMember Fixture generated on: 2011-11-30 11:24:52 : 1322670292 */

/**
 * CenterMemberFixture
 *
 */
class CenterMemberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'center_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'member', 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => 'fan,member,staff', 'charset' => 'latin1'),
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'note' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
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
			'center_id' => 1,
			'user_id' => 1,
			'type' => 'Lorem ipsum dolor ',
			'id' => 1,
			'note' => 'Lorem ipsum dolor sit amet'
		),
	);
}
