<?php
/* Court Test cases generated on: 2011-12-12 11:24:59 : 1323707099*/
App::uses('Court', 'Model');

/**
 * Court Test Case
 *
 */
class CourtTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.court');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Court = ClassRegistry::init('Court');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Court);

		parent::tearDown();
	}

}
