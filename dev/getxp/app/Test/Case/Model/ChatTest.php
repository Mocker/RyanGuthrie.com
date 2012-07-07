<?php
/* Chat Test cases generated on: 2012-02-27 11:03:21 : 1330358601*/
App::uses('Chat', 'Model');

/**
 * Chat Test Case
 *
 */
class ChatTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.chat');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Chat = ClassRegistry::init('Chat');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Chat);

		parent::tearDown();
	}

}
