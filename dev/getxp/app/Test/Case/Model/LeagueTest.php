<?php
/* League Test cases generated on: 2011-12-09 15:37:08 : 1323463028*/
App::uses('League', 'Model');

/**
 * League Test Case
 *
 */
class LeagueTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.league');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->League = ClassRegistry::init('League');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->League);

		parent::tearDown();
	}

}
