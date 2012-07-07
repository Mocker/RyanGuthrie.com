<?php
/* ShopItem Test cases generated on: 2012-02-27 16:24:53 : 1330377893*/
App::uses('ShopItem', 'Model');

/**
 * ShopItem Test Case
 *
 */
class ShopItemTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.shop_item');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->ShopItem = ClassRegistry::init('ShopItem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShopItem);

		parent::tearDown();
	}

}
