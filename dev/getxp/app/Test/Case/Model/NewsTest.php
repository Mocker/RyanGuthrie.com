<?php
/* News Test cases generated on: 2012-02-27 16:24:24 : 1330377864*/
App::uses('News', 'Model');

/**
 * News Test Case
 *
 */
class NewsTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.news');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->News = ClassRegistry::init('News');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->News);

		parent::tearDown();
	}

}
