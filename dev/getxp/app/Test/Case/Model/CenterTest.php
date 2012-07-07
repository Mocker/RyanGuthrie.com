<?php
/* Center Test cases generated on: 2011-11-30 11:22:27 : 1322670147*/
App::uses('Center', 'Model');

/**
 * Center Test Case
 *
 */
class CenterTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.center');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Center = ClassRegistry::init('Center');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Center);

		parent::tearDown();
	}

}
