<?php
/* Reservation Test cases generated on: 2011-12-12 11:25:38 : 1323707138*/
App::uses('Reservation', 'Model');

/**
 * Reservation Test Case
 *
 */
class ReservationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.reservation');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Reservation = ClassRegistry::init('Reservation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Reservation);

		parent::tearDown();
	}

}
