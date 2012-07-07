<?php
/* Friend Test cases generated on: 2011-12-02 12:40:47 : 1322847647*/
App::uses('Friend', 'Model');

/**
 * Friend Test Case
 *
 */
class FriendTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.friend');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Friend = ClassRegistry::init('Friend');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Friend);

		parent::tearDown();
	}

}
