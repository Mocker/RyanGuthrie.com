<?php
/* Photo Test cases generated on: 2012-02-27 16:24:40 : 1330377880*/
App::uses('Photo', 'Model');

/**
 * Photo Test Case
 *
 */
class PhotoTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.photo');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Photo = ClassRegistry::init('Photo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Photo);

		parent::tearDown();
	}

}
