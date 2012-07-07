<?php
/* FriendRequest Test cases generated on: 2011-12-02 12:40:37 : 1322847637*/
App::uses('FriendRequest', 'Model');

/**
 * FriendRequest Test Case
 *
 */
class FriendRequestTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.friend_request');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->FriendRequest = ClassRegistry::init('FriendRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FriendRequest);

		parent::tearDown();
	}

}
