<?php
/* Competitor Test cases generated on: 2011-12-09 18:18:19 : 1323472699*/
App::uses('Competitor', 'Model');

/**
 * Competitor Test Case
 *
 */
class CompetitorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.competitor');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Competitor = ClassRegistry::init('Competitor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Competitor);

		parent::tearDown();
	}

}
