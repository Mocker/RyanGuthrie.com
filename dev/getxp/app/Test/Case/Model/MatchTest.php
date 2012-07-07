<?php
/* Match Test cases generated on: 2011-12-09 15:37:20 : 1323463040*/
App::uses('Match', 'Model');

/**
 * Match Test Case
 *
 */
class MatchTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.match');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Match = ClassRegistry::init('Match');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Match);

		parent::tearDown();
	}

}
