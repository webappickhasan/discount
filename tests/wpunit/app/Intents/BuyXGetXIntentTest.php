<?php

namespace Disco\Tests\WPUnit\App\Intents;

use Disco\App\Intents\BuyXGetXIntent;
use Disco\App\Utility\Config;
use Disco\App\Utility\Settings;

class BuyXGetXIntentTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	protected $products;
	protected $cart;

	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->products = array(
			$product1 = $this->tester->create_simple_product( 1 ),
			$product2 = $this->tester->create_simple_product( 2 ),
			$product3 = $this->tester->create_simple_product( 3 ),
			$product4 = $this->tester->create_simple_product( 4 ),
		);

		$this->cart = $this->tester->add_product_to_cart(
			$this->products,
			array(
				$this->products[0]->get_id() => 2,
				$this->products[1]->get_id() => 3,
				$this->products[2]->get_id() => 4,
				$this->products[3]->get_id() => 8,
			)
		);

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		foreach ( $this->products as $product ) {
			wp_delete_post( $product->get_id(), true );
		}

		// Remove all products from cart.
		$this->cart->empty_cart();

		// Then...
		parent::tearDown();
	}

	public function get_discounts( $data ) {
		$campaign = new Config( $data );
		$settings = Settings::get( 'all' );
		$intent   = new BuyXGetXIntent( $campaign, $settings );

		return $intent->get_discounts( $this->cart->get_cart(), $this->cart );
	}

	// Tests
	public function testBuyXGetXIntent() {

		$data = array(
			'id'              => 1,
			'status'          => '1',
			'discount_intent' => 'BuyXGetX',
			'bogo_type'       => 'all',
			'products'        => array(
				'all',
			),
		);

		// Test product percent and fixed discount by item price.
		$data ['discount_rules'] = array(
			array(
				'min'            => '2', // Minimum price or quantity
				'max'            => '3', // Maximum price or quantity
				'discount_type'  => 'percent',
				'discount_value' => '10',
				'get_quantity'   => '1',
				'recursive'      => 'yes',
			),
			array(
				'min'            => '2', // Minimum price or quantity
				'max'            => '3', // Maximum price or quantity
				'discount_type'  => 'fixed',
				'discount_value' => '10',
				'get_quantity'   => '1',
				'recursive'      => 'no',
			),
			array(
				'min'            => '3', // Minimum price or quantity
				'max'            => '4', // Maximum price or quantity
				'discount_type'  => 'free',
				'discount_value' => '10',
				'get_quantity'   => '1',
				'recursive'      => 'no',
			),
		);

		$discounts = $this->get_discounts( $data );

		codecept_debug( $discounts );

		// Assert Product 1 For Rule 1
		// Product 1 has 2 quantity and price is 10. 2 quantity is in range of 2 to 3
		// So, 10% discount should be applied
		$expected_discount = $this->products[0]->get_price() - ( $this->products[0]->get_price() * 0.1 );
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[0]->get_id() ]['prices'], false ) );

		// Assert Product 1 For Rule 2
		// Product 1 has 2 quantity and price is 10. 2 quantity is in range of 2 to 3
		// So, $10 discount should be applied
		$expected_discount = $this->products[0]->get_price() - 10;
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[0]->get_id() ]['prices'], false ) );

		// Assert Product 2 For Rule 1
		// Product 2 has 3 quantity and price is 80. 3 quantity is in range of 2 to 3
		// So, 10% discount should be applied
		$expected_discount = $this->products[1]->get_price() - ( $this->products[1]->get_price() * 0.1 );
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[1]->get_id() ]['prices'], false ) );

		// Assert Product 2 For Rule 2
		// Product 2 has 3 quantity and price is 80. 3 quantity is in range of 2 to 3
		// So, $10 discount should be applied
		$expected_discount = $this->products[1]->get_price() - 10;
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[1]->get_id() ]['prices'], false ) );

		// Assert Product 2 For Rule 3
		// Product 2 has 3 quantity and price is 80. 3 quantity is in range of 3 to 4
		// So, 100% discount should be applied
		$expected_discount = $this->products[1]->get_price() - $this->products[1]->get_price();
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[1]->get_id() ]['prices'], false ) );

		// Assert Product 3 For Rule 1
		// Product 3 has 4 quantity and price is 80. 4 quantity is in range of 3 to 4
		// So, 10% discount should be applied
		$expected_discount = $this->products[2]->get_price() - ( $this->products[2]->get_price() * 0.1 );
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[2]->get_id() ]['prices'], false ) );

		// Assert Product 3 For Rule 3
		// Product 3 has 4 quantity and price is 80. 4 quantity is in range of 3 to 4
		// So, 100% discount should be applied
		$expected_discount = $this->products[2]->get_price() - $this->products[2]->get_price();
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[2]->get_id() ]['prices'], false ) );

		// Assert Product 4 For Rule 1
		// Product 4 has 8 quantity and price is 80. 8 quantity is in range of 3 to 4 as recursive is yes.
		// So, 10% discount should be applied
		$expected_discount = $this->products[3]->get_price() - ( $this->products[3]->get_price() * 0.1 );
		$this->assertTrue( in_array( $expected_discount, $discounts[ $this->products[3]->get_id() ]['prices'], false ) );
	}
}
