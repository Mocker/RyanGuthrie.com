<?php
/* Wave Test cases generated on: 2011-12-05 11:34:29 : 1323102869*/
App::uses('Wave', 'Model');

/**
 * Wave Test Case
 *
 */
class WaveTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wave');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Wave = ClassRegistry::init('Wave');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wave);

		parent::tearDown();
	}

}
