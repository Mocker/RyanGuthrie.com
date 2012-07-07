<?php
/* Message Test cases generated on: 2011-12-02 12:41:05 : 1322847665*/
App::uses('Message', 'Model');

/**
 * Message Test Case
 *
 */
class MessageTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.message');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Message = ClassRegistry::init('Message');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Message);

		parent::tearDown();
	}

}
