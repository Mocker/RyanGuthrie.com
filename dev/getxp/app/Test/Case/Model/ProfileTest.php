<?php
/* Profile Test cases generated on: 2011-11-09 15:21:00 : 1320870060*/
App::uses('Profile', 'Model');

/**
 * Profile Test Case
 *
 */
class ProfileTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.profile', 'app.user', 'app.sport');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Profile = ClassRegistry::init('Profile');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Profile);

		parent::tearDown();
	}

}
