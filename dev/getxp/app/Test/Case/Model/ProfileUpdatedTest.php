<?php
/* ProfileUpdated Test cases generated on: 2011-12-08 15:01:56 : 1323374516*/
App::uses('ProfileUpdated', 'Model');

/**
 * ProfileUpdated Test Case
 *
 */
class ProfileUpdatedTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.profile_updated');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->ProfileUpdated = ClassRegistry::init('ProfileUpdated');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProfileUpdated);

		parent::tearDown();
	}

}
