<?php
/* Bulletin Test cases generated on: 2012-02-27 16:24:00 : 1330377840*/
App::uses('Bulletin', 'Model');

/**
 * Bulletin Test Case
 *
 */
class BulletinTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.bulletin');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Bulletin = ClassRegistry::init('Bulletin');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Bulletin);

		parent::tearDown();
	}

}
