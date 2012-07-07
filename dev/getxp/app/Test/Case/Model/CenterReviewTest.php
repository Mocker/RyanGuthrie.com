<?php
/* CenterReview Test cases generated on: 2011-11-30 11:24:20 : 1322670260*/
App::uses('CenterReview', 'Model');

/**
 * CenterReview Test Case
 *
 */
class CenterReviewTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.center_review');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->CenterReview = ClassRegistry::init('CenterReview');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CenterReview);

		parent::tearDown();
	}

}
