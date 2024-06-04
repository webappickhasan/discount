<?php

namespace Disco\Tests\WPUnit\App\Intents;

use Disco\App\Intents\ProductIntent;
use Disco\App\Utility\Config;
use Disco\App\Utility\Settings;

class ProductIntentTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function testProductIntent() {

		$settings = Settings::get( 'all' );
		$product1 = $this->tester->create_simple_product( 1 );
		$product2 = $this->tester->create_simple_product( 2 );
		$product3 = $this->tester->create_simple_product( 3 );
		$cart     = $this->tester->add_product_to_cart( array( $product1, $product2, $product3 ) );

		$data = array(
			'id'              => 1,
			'status'          => '1',
			'discount_intent' => 'Product',
			'products'        => array( 'all' ),
			'discount_rules'  => array(
				array(
					'discount_type'  => 'percent',
					'discount_value' => '10',
				),
			),
		);

		// Test cart percent discount
		$campaign = new Config( $data );
		$intent   = new ProductIntent( $campaign, $settings );
		$discount = $intent->get_discounts( $product1->get_price(), $product1 );

		$expected = $product1->get_price() - ( $product1->get_price() * 0.1 );
		$this->assertEquals( $expected, $discount );
	}
}
