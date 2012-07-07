<?php
/* Game Test cases generated on: 2012-02-27 16:24:11 : 1330377851*/
App::uses('Game', 'Model');

/**
 * Game Test Case
 *
 */
class GameTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.game');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Game = ClassRegistry::init('Game');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Game);

		parent::tearDown();
	}

}
