<?php
/* Competition Test cases generated on: 2011-12-02 09:07:36 : 1322834856*/
App::uses('Competition', 'Model');

/**
 * Competition Test Case
 *
 */
class CompetitionTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.competition');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Competition = ClassRegistry::init('Competition');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Competition);

		parent::tearDown();
	}

}
