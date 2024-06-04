<?php

namespace Disco\Tests\WPUnit\App\Intents;

use Disco\App\Intents\BundleIntent;
use Disco\App\Utility\Config;
use Disco\App\Utility\Settings;

class BundleIntentTest extends \Codeception\TestCase\WPTestCase {
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
		);

		$this->cart = $this->tester->add_product_to_cart(
			$this->products,
			array(
				$this->products[0]->get_id() => 2,
				$this->products[1]->get_id() => 3,
				$this->products[2]->get_id() => 4,
			)
		);

		// Your set-up methods here.
	}

	public function tearDown(): void {
		// Your tear-down methods here.

		foreach ( $this->products as $product ) {
			wp_delete_post( $product->get_id(), true );
		}

		// Remove all products from the cart.
		$this->cart->empty_cart();

		// Then...
		parent::tearDown();
	}

	public function get_discounts( $data ) {
		$campaign = new Config( $data );
		$settings = Settings::get( 'all' );
		$intent   = new BundleIntent( $campaign, $settings );

		return $intent->get_discounts( $this->cart->get_cart(), $this->cart );
	}

	// Tests
	public function testBundleIntent() {
		$data = array(
			'id'              => 1,
			'status'          => '1',
			'discount_intent' => 'Bundle',
			'products'        => array(
				'all',
			),
			'discount_rules'  => array(
				array(
					'min'            => '2', // Minimum price or quantity 720%72=10
					'discount_type'  => 'percent',
					'discount_value' => '10',
					'recursive'      => 'yes',
				),
				array(
					'min'            => '4', // Minimum price or quantity
					'discount_type'  => 'fixed',
					'discount_value' => '10',
					'recursive'      => 'no',
				),
			),
		);

		// Test cart percent and fixed discount by cart subtotal
		$discounts = $this->get_discounts( $data );
		codecept_debug( $discounts );

		// Assert Product 1 (It will have discounted price 72)
		$expected = $this->products[0]->get_price() - ( $this->products[0]->get_price() * 10 / 100 );
		$actual   = $discounts[ $this->products[0]->get_id() ]['prices'];
		$this->assertTrue( in_array( $expected, $actual, false ) );

		// Assert Product 2 (It will not have any discounted )
		$this->assertFalse( isset( $discounts[ $this->products[1]->get_id() ] ) );

		// Assert Product 3 (It will have discounted price 70 & 72)
		$expected1 = $this->products[2]->get_price() - ( $this->products[2]->get_price() * 10 / 100 );
		$expected2 = $this->products[2]->get_price() - 10;
		$actual    = $discounts[ $this->products[2]->get_id() ]['prices'];
		$this->assertTrue( in_array( $expected1, $actual, false ) );
		$this->assertTrue( in_array( $expected2, $actual, false ) );
	}
}
