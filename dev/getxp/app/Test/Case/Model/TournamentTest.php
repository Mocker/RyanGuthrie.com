<?php
/* Tournament Test cases generated on: 2011-12-12 14:31:33 : 1323718293*/
App::uses('Tournament', 'Model');

/**
 * Tournament Test Case
 *
 */
class TournamentTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.tournament');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Tournament = ClassRegistry::init('Tournament');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tournament);

		parent::tearDown();
	}

}
