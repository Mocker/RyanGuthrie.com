<?php
/* CenterMember Test cases generated on: 2011-11-30 11:24:52 : 1322670292*/
App::uses('CenterMember', 'Model');

/**
 * CenterMember Test Case
 *
 */
class CenterMemberTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.center_member');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->CenterMember = ClassRegistry::init('CenterMember');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CenterMember);

		parent::tearDown();
	}

}
