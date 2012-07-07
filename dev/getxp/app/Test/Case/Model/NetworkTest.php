<?php
/* Network Test cases generated on: 2011-12-02 09:06:02 : 1322834762*/
App::uses('Network', 'Model');

/**
 * Network Test Case
 *
 */
class NetworkTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.network');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Network = ClassRegistry::init('Network');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Network);

		parent::tearDown();
	}

}
