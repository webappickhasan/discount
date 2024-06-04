<?php

namespace Disco\Tests\WPUnit;

use Disco\App\Campaign;
use Disco\App\Disco;
use Disco\App\Intents\ProductIntent;
use Disco\App\Utility\Config;
use Disco\App\Utility\Settings;
use stdClass;
use WC_Product;

class DiscoTest extends \Codeception\TestCase\WPTestCase {
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

	public function testProductDiscountIsAppliedWhenProductIsInstanceOfWCProduct() {
		// Create & Save a Campaign
		// Create a campaign config array
		$config     = array(
			'name'            => 'Test Campaign',
			'status'          => '1',
			'discount_intent' => 'Product',
			'discount_rules'  => array(
				array(
					'min'            => 5,
					'discount_type'  => 'percent',
					'discount_value' => 10,
					'get_quantity'   => '',
					'get_ids'        => array(),
					'discount_label' => '10% off',
				),
			),
		);
		$config_obj = new Config( $config );
		$campaign   = new Campaign();
		$save       = $campaign->save_campaign( $config );

		$campaignMock = $this->createMock( Campaign::class );
		$campaignMock->method( 'get_campaigns' )->with( '1' )->willReturn( array( $config_obj ) );
		$campaignMock->discount_intent = 'Product';

		// codecept_debug( $campaign->get_campaigns() );

		// Create a settings mock
		$settingsMock = \Mockery::mock( 'overload:Settings' );
		$settingsMock->shouldReceive( 'get' )->with( 'min_max_discount_amount' )->andReturn( 'min' );
		// Create a helper mock
		$helperMock = \Mockery::mock( 'overload:Helper' );
		$helperMock->shouldReceive( 'is_in_valid_data' )->andReturn( true );

		// Create an intent factory mock
		$intentFactoryMock = \Mockery::mock( 'overload:IntentFactory' );
		$intentFactoryMock->shouldReceive( 'get_intent' )->andReturn( new ProductIntent( new Config( array() ), new Settings() ) );

		$disco   = new Disco();
		$product = $this->createMock( WC_Product::class );
		$product->method( 'get_price' )->willReturn( 100.0 );
		$price = 100.0;

		$discountedPrice = $disco->get_product_discounted_price( $price, $product );
		codecept_debug( $discountedPrice );

		$this->assertLessThan( $price, $discountedPrice );
	}

	public function testOriginalPriceIsReturnedWhenProductIsNotInstanceOfWCProduct() {
		$disco   = new Disco();
		$product = new stdClass();
		$price   = 100.0;

		$discountedPrice = $disco->get_product_discounted_price( $price, $product );

		$this->assertEquals( $price, $discountedPrice );
	}

	public function testOriginalPriceIsReturnedWhenNoIntentsAreAvailable() {
		$disco   = new Disco();
		$product = $this->createMock( WC_Product::class );
		$price   = 100.0;

		// Assuming prepare_intents method is modified to accept intents as parameter for testing
		$disco->prepare_intents( array() );

		$discountedPrice = $disco->get_product_discounted_price( $price, $product );

		$this->assertEquals( $price, $discountedPrice );
	}

	public function testOriginalPriceIsReturnedWhenNoDiscountIsApplied() {
		$disco   = new Disco();
		$product = $this->createMock( WC_Product::class );
		$price   = 100.0;

		// Assuming prepare_intents method is modified to accept intents as parameter for testing
		// and get_discounts method in Intent class returns empty array
		$disco->prepare_intents( array( 'Product' ) );

		$discountedPrice = $disco->get_product_discounted_price( $price, $product );

		$this->assertEquals( $price, $discountedPrice );
	}
}
