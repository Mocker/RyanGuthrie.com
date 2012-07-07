<?php
/* Sport Test cases generated on: 2011-11-09 15:20:42 : 1320870042*/
App::uses('Sport', 'Model');

/**
 * Sport Test Case
 *
 */
class SportTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.sport', 'app.profile');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Sport = ClassRegistry::init('Sport');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Sport);

		parent::tearDown();
	}

}
