<?php
/* Competition Fixture generated on: 2011-12-02 09:07:36 : 1322834856 */

/**
 * CompetitionFixture
 *
 */
class CompetitionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'network_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 70, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'location' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'zip' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'fee' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'fee_paypal' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'comment' => 'paypal email for a button to accept payments', 'charset' => 'latin1'),
		'organizer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => 'user id of organizer'),
		'approved' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => 'if network approved this '),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'charset' => 'latin1'),
		'deadline' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'time' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
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
			'network_id' => 1,
			'center_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'location' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'zip' => 'Lorem ip',
			'fee' => 'Lorem ipsum dolor sit amet',
			'fee_paypal' => 'Lorem ipsum dolor sit amet',
			'organizer_id' => 1,
			'approved' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'deadline' => '2011-12-02 09:07:36',
			'time' => '2011-12-02 09:07:36'
		),
	);
}
