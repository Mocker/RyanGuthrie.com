<?php
/* Mains Test cases generated on: 2011-11-01 03:11:10 : 1320135070*/
App::uses('MainsController', 'Controller');

/**
 * TestMainsController *
 */
class TestMainsController extends MainsController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * MainsController Test Case
 *
 */
class MainsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.main');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Mains = new TestMainsController();
		$this->Mains->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mains);

		parent::tearDown();
	}

}
