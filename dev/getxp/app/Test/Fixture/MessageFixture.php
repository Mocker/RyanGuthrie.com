<?php
/* Message Fixture generated on: 2011-12-02 12:41:05 : 1322847665 */

/**
 * MessageFixture
 *
 */
class MessageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'message' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'profile_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'to_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'sent' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'collate' => NULL, 'comment' => ''),
		'read' => array('type' => 'integer', 'null' => false, 'default' => '0', 'collate' => NULL, 'comment' => ''),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'mail', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'comment' => '"mail","chat","system"', 'charset' => 'latin1'),
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
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
			'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'profile_id' => 1,
			'to_id' => 1,
			'sent' => 1322847665,
			'read' => 1,
			'type' => 'Lorem ip',
			'id' => 1
		),
	);
}
