<?php

namespace app\Intents;

use Codeception\TestCase\WPTestCase;
use Disco\App\Campaign;
use Disco\App\Intents\IntentHelper;
use Disco\App\Intents\ProductIntent;
use Disco\App\Utility\Config;
use Disco\App\Utility\Helper;
use Disco\App\Utility\Settings;
use Disco\Backend\ActDeact;

class IntentHelperTest extends WPTestCase {
	use IntentHelper;

	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		ActDeact::create_plugin_table();
		$this->tester->create_db_table();
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	public function testPrepareIntentWithEmptyCampaign() {
		// Test prepare intent method with empty campaigns
		$campaign = $this->createMock( Campaign::class );
		$campaign->method( 'get_campaigns' )->willReturn( array() );

		$result = $this->prepare_intents( array() );

		$this->assertEmpty( $result );
	}

	// public function testPreparesIntentsCorrectlyWithAProductCampaign() {
	// Create a campaign config array
	// $config = [
	// 'name'            => 'Test Campaign',
	// 'discount_intent' => 'Product',
	// 'discount_rules'  => [
	// [
	// 'min'            => 5,
	// 'discount_type'  => 'percent',
	// 'discount_value' => 10,
	// 'get_quantity'   => '',
	// 'get_ids'        => [],
	// 'discount_label' => '10% off'
	// ]
	// ]
	// ];
	//
	// Create a campaign mock
	// $campaignMock = $this->createMock( Campaign::class );
	// $campaignMock->method( 'get_campaigns' )->willReturn( [ new Config( $config ) ] );
	//
	// Create a settings mock
	// $settingsMock = \Mockery::mock( 'overload:Settings' );
	// $settingsMock->shouldReceive( 'get' )->with( 'min_max_discount_amount' )->andReturn( 'min' );
	// Create a helper mock
	// $helperMock = \Mockery::mock( 'overload:Helper' );
	// $helperMock->shouldReceive( 'is_in_valid_data' )->andReturn( true );
	// Create an intent factory mock
	// $intentFactoryMock = \Mockery::mock( 'overload:IntentFactory' );
	// $intentFactoryMock->shouldReceive( 'get_intent' )->with( $campaignMock, $settingsMock )
	// ->andReturn( new ProductIntent( new Config( $config ), new Settings() ) );
	//
	// $result = $this->prepare_intents( [ 'Product' ] );
	// codecept_debug( $result );
	// $this->assertEmpty( $result );
	// }

	public function testPreparesIntentsCorrectlyWithEmptyIntentsAndEmptyCampaign() {

		$campaignMock = $this->createMock( Campaign::class );
		$campaignMock->method( 'get_campaigns' )->willReturn( array() );

		// Create a settings mock
		$settingsMock = \Mockery::mock( 'overload:Settings' );
		$settingsMock->shouldReceive( 'get' )->with( 'min_max_discount_amount' )->andReturn( 'min' );
		// Create a helper mock
		$helperMock = \Mockery::mock( 'overload:Helper' );
		$helperMock->shouldReceive( 'is_in_valid_data' )->andReturn( true );

		// Create an intent factory mock
		$intentFactoryMock = \Mockery::mock( 'overload:IntentFactory' );
		$intentFactoryMock->shouldReceive( 'get_intent' )->andReturn( new ProductIntent( new Config( array() ), new Settings() ) );

		$result = $this->prepare_intents( array() );

		$this->assertEmpty( $result );
	}

	public function testPreparesIntentsCorrectlyWithValidCampaigns() {
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

		$result = $this->prepare_intents( array( 'Product' ) );
		// $product = $this->tester->create_simple_product( 1 );
		// $product->set_price( 20.0 );
		// $product->save();
		// codecept_debug( $result[0]->get_discounts( 20.00, $product ) );
		$this->assertInstanceOf( ProductIntent::class, $result[0] );
	}

	public function testskipsIntentsInSkipList() {

		$campaignMock = $this->createMock( Campaign::class );
		$campaignMock->method( 'get_campaigns' )->willReturn(
			array(
				(object) array(
					'discount_intent' => 'BOGO',
					'bogo_type'       => 'products',
				),
			)
		);
		$settingsMock = $this->createMock( Settings::class );

		$result = $this->prepare_intents( array( 'BOGO' ) );

		$this->assertEmpty( $result );
	}

	public function testskipsInvalidCampaigns() {

		$campaignMock = $this->createMock( Campaign::class );
		$campaignMock->method( 'get_campaigns' )->willReturn(
			array(
				(object) array(
					'discount_intent' => 'BOGO',
					'bogo_type'       => 'products',
				),
			)
		);
		$settingsMock = $this->createMock( Settings::class );
		$helperMock   = $this->createMock( Helper::class );
		$helperMock->method( 'is_in_valid_data' )->willReturn( false );

		$result = $this->prepare_intents( array() );

		$this->assertEmpty( $result );
	}


	public function testVerifyRulesReturnsFalseWhenRuleIsEmpty() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Product' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
		);
		$rule = array();

		$this->assertFalse( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testVerifyRulesReturnsTrueWhenCampaignIsProductAndRuleIsValid() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Product' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
		);
		$rule = array(
			'discount_value' => 10,
			'discount_type'  => 'percent',
		);

		$this->assertTrue( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testVerifyRulesReturnsFalseWhenCampaignIsProductAndRuleIsInvalid() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Product' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
		);
		$rule = array( 'discount_value' => 10 );

		$this->assertFalse( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testVerifyRulesReturnsTrueWhenCampaignIsBulkAndRuleIsValid() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Bulk' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
			'quantity'     => 5,
		);
		$rule = array(
			'min' => 3,
			'max' => 10,
		);

		$this->assertTrue( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testVerifyRulesReturnsFalseWhenCampaignIsBulkAndRuleIsInvalid() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Bulk' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
			'quantity'     => 2,
		);
		$rule = array(
			'min' => 3,
			'max' => 10,
		);

		$this->assertFalse( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testVerifyRulesReturnsFalseWhenCampaignIsUnknown() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'Unknown' );
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
		);
		$rule = array(
			'discount_value' => 10,
			'discount_type'  => 'percent',
		);

		$this->assertFalse( $this->verify_rules( $campaign, $item, $rule ) );
	}

	public function testCartItemIdReturnsProductIdWhenNoVariationId() {
		// Given a cart item with a product ID and no variation ID
		$item = array(
			'product_id'   => 123,
			'variation_id' => 0,
		);

		// When we call the get_cart_item_id method
		$id = $this->get_cart_item_id( $item );

		// Then we should get the product ID
		$this->assertEquals( 123, $id );
	}

	public function testCartItemIdReturnsVariationIdWhenPresent() {
		// Given a cart item with a product ID and a variation ID
		$item = array(
			'product_id'   => 123,
			'variation_id' => 456,
		);

		// When we call the get_cart_item_id method
		$id = $this->get_cart_item_id( $item );

		// Then we should get the variation ID
		$this->assertEquals( 456, $id );
	}

	public function testBasisForCartReturnsZeroWhenCartIsEmpty() {
		$campaign = $this->createMock( Config::class );
		$cart     = $this->createMock( \WC_Cart::class );
		$cart->method( 'is_empty' )->willReturn( true );

		$this->assertEquals( 0, $this->get_basis_for_cart( $campaign, $cart ) );
	}

	public function testBasisForCartReturnsCartQuantityWhenCampaignBasedOnCartQuantity() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_based_on' )->willReturn( 'cart_quantity' );

		$cart = $this->createMock( \WC_Cart::class );
		$cart->method( 'is_empty' )->willReturn( false );
		$cart->method( 'get_cart_contents_count' )->willReturn( 5 );

		$this->assertEquals( 5, $this->get_basis_for_cart( $campaign, $cart ) );
	}

	public function testBasisForCartReturnsCartSubtotalWhenCampaignNotBasedOnCartQuantity() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_based_on' )->willReturn( 'not_cart_quantity' );

		$cart = $this->createMock( \WC_Cart::class );
		$cart->method( 'is_empty' )->willReturn( false );
		$cart->method( 'get_subtotal' )->willReturn( 100.0 );

		$this->assertEquals( 100.0, $this->get_basis_for_cart( $campaign, $cart ) );
	}

	public function testBasisForItemReturnsItemQuantityWhenItemIsArrayWithQuantity() {
		$item = array( 'quantity' => 5 );

		$this->assertEquals( 5, $this->get_basis_for_item( $item ) );
	}

	public function testBasisForItemReturnsZeroWhenItemIsArrayWithoutQuantity() {
		$item = array( 'not_quantity' => 5 );

		$this->assertEquals( 0, $this->get_basis_for_item( $item ) );
	}

	public function testBasisForItemReturnsZeroWhenItemIsNeitherProductNorArray() {
		$item = 'not_a_product_or_array';

		$this->assertEquals( 0, $this->get_basis_for_item( $item ) );
	}

	public function testCalculateDiscountReturnsCorrectValueForPercentDiscountType() {
		$cost           = 100.0;
		$discount_type  = 'percent';
		$discount_value = 10.0;

		$this->assertEquals( 10.0, $this->calculate_discount( $cost, $discount_type, $discount_value ) );
	}

	public function testCalculateDiscountReturnsCorrectValueForFixedDiscountType() {
		$cost           = 100.0;
		$discount_type  = 'fixed';
		$discount_value = 10.0;

		$this->assertEquals( 10.0, $this->calculate_discount( $cost, $discount_type, $discount_value ) );
	}

	public function testCalculateDiscountReturnsCostForUnknownDiscountType() {
		$cost           = 100.0;
		$discount_type  = 'unknown';
		$discount_value = 10.0;

		$this->assertEquals( 100.0, $this->calculate_discount( $cost, $discount_type, $discount_value ) );
	}

	public function testDiscountedPriceReturnsCorrectValueWhenDiscountIsLessThanCost() {
		$cost              = 100.0;
		$discounted_amount = 20.0;

		$this->assertEquals( 80.0, $this->discounted_price( $cost, $discounted_amount ) );
	}

	public function testDiscountedPriceReturnsCostWhenDiscountIsGreaterThanCost() {
		$cost              = 100.0;
		$discounted_amount = 120.0;

		$this->assertEquals( 100.0, $this->discounted_price( $cost, $discounted_amount ) );
	}

	public function testDiscountedPriceReturnsCostWhenDiscountIsEqualToCost() {
		$cost              = 100.0;
		$discounted_amount = 100.0;

		$this->assertEquals( 0.0, $this->discounted_price( $cost, $discounted_amount ) );
	}

	public function testIsMultipleReturnsTrueWhenNumberIsMultipleOfAnother() {
		$number = 2;
		$of     = 4;

		$this->assertTrue( $this->is_multiple( $number, $of ) );
	}

	public function testIsMultipleReturnsFalseWhenNumberIsNotMultipleOfAnother() {
		$number = 3;
		$of     = 5;

		$this->assertFalse( $this->is_multiple( $number, $of ) );
	}

	public function testIsMultipleReturnsFalseWhenNumberIsGreaterThanAnother() {
		$number = 5;
		$of     = 3;

		$this->assertFalse( $this->is_multiple( $number, $of ) );
	}

	public function testIsMultipleReturnsTrueWhenNumberIsEqualToAnother() {
		$number = 3;
		$of     = 3;

		$this->assertTrue( $this->is_multiple( $number, $of ) );
	}

	public function testProductIsInCategoryWhenCategoriesMatch() {
		// Create 2 WooCommerce Category Terms and save.
		$cat1 = wp_insert_term( 'Category 1', 'product_cat' );
		$cat2 = wp_insert_term( 'Category 2', 'product_cat' );

		// Create a WooCommerce Product and assign the 2 categories to it.
		$product_id = wp_insert_post(
			array(
				'post_title'  => 'Test Product',
				'post_type'   => 'product',
				'post_status' => 'publish',
			)
		);
		wp_set_object_terms( $product_id, array( $cat1['term_id'], $cat2['term_id'] ), 'product_cat' );

		// Check if the product is in the first category
		$this->assertTrue( $this->is_in_category( $product_id, array( $cat1['term_id'], $cat2['term_id'] ) ) );
	}

	public function testProductIsNotInCategoryWhenCategoriesDoNotMatch() {
		// Create 2 WooCommerce Category Terms and save.
		$cat1 = wp_insert_term( 'Category 1', 'product_cat' );
		$cat2 = wp_insert_term( 'Category 2', 'product_cat' );

		// Create a WooCommerce Product and assign the 2 categories to it.
		$product_id = wp_insert_post(
			array(
				'post_title'  => 'Test Product',
				'post_type'   => 'product',
				'post_status' => 'publish',
			)
		);
		wp_set_object_terms( $product_id, array( $cat1['term_id'], $cat2['term_id'] ), 'product_cat' );

		$this->assertFalse( $this->is_in_category( $product_id, array( 100, 200, 300 ) ) );
	}

	public function testProductIsNotInCategoryWhenProductHasNoCategories() {
		$product_id = 123;
		$categories = array( 1, 2, 3 );

		$this->assertFalse( $this->is_in_category( $product_id, $categories ) );
	}

	public function testProductIsNotInCategoryWhenCategoriesAreEmpty() {
		// Create 2 WooCommerce Category Terms and save.
		$cat1 = wp_insert_term( 'Category 1', 'product_cat' );
		$cat2 = wp_insert_term( 'Category 2', 'product_cat' );

		// Create a WooCommerce Product and assign the 2 categories to it.
		$product_id = wp_insert_post(
			array(
				'post_title'  => 'Test Product',
				'post_type'   => 'product',
				'post_status' => 'publish',
			)
		);
		wp_set_object_terms( $product_id, array( $cat1['term_id'], $cat2['term_id'] ), 'product_cat' );

		$this->assertFalse( $this->is_in_category( $product_id, array() ) );
	}

	public function testGetItemDiscountsReturnsCorrectDiscounts() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_rules' )->willReturn(
			array(
				array(
					'min'            => 5,
					'discount_type'  => 'percent',
					'discount_value' => 10,
					'get_quantity'   => 1,
					'get_ids'        => array( 1 ),
					'discount_label' => '10% off',
				),
			)
		);
		$campaign->method( 'get_discount_intent' )->willReturn( 'Product' );

		$item = $this->createMock( \WC_Product::class );
		$item->method( 'get_price' )->willReturn( 100.0 );

		$items = array(
			array(
				'data'         => $item,
				'key'          => 'item_key_1',
				'product_id'   => 1,
				'variation_id' => 0,
			),
		);

		$discounts = $this->get_item_discounts( $campaign, $items );

		$key = array_key_first( $discounts[1]['prices'] );

		$this->assertArrayHasKey( 1, $discounts );
		$this->assertEquals( 100.0, $discounts[1]['original_price'] );
		$this->assertEquals( 'item_key_1', $discounts[1]['item_key'] );
		$this->assertEquals( 90.0, $discounts[1]['prices'][ $key ] );
		$this->assertEquals( 'Product', $discounts[1]['intent'][ $key ] );
		$this->assertEquals( array( 1 ), $discounts[1]['get_ids'] );
		$this->assertEquals( 10.0, $discounts[1]['discounts'][ $key ] );
		$this->assertEquals( '10% off', $discounts[1]['labels'][ $key ] );
		$this->assertEquals( 1, $discounts[1]['quantities'][ $key ] );
		$this->assertEquals( '', $discounts[1]['bogo_qty'][ $key ] );

		$this->assertEquals(
			array(
				'quantity' => 1,
				'label'    => '10% off',
			),
			$discounts[1]['offers'][ $key ]
		);
	}

	public function testGetItemDiscountsReturnsEmptyArrayWhenNoDiscountRules() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_rules' )->willReturn( array() );

		$item = $this->createMock( \WC_Product::class );
		$item->method( 'get_price' )->willReturn( 100.0 );

		$items = array(
			array(
				'data'         => $item,
				'key'          => 'item_key_1',
				'product_id'   => 1,
				'variation_id' => 0,
			),
		);

		$discounts = $this->get_item_discounts( $campaign, $items );

		$this->assertEmpty( $discounts );
	}

	public function testGetItemDiscountsReturnsEmptyArrayWhenNoItems() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_rules' )->willReturn(
			array(
				array(
					'discount_type'  => 'percent',
					'discount_value' => 10,
					'get_quantity'   => 1,
					'get_ids'        => array( 1 ),
					'discount_label' => '10% off',
				),
			)
		);

		$items = array();

		$discounts = $this->get_item_discounts( $campaign, $items );

		$this->assertEmpty( $discounts );
	}

	public function testGetItemsDiscountForBogoReturnsEmptyArrayWhenGetQuantityIsNotSetOrLessThanTwo() {
		$id        = 1;
		$discounts = array( 'discounts' => array( 1 => 10.0 ) );
		$r         = 1;
		$rule      = array( 'get_quantity' => 1 );
		$item      = array(
			'line_subtotal' => 100.0,
			'quantity'      => 5,
		);

		$this->assertEquals( array(), $this->get_items_discount_for_bogo( $id, $discounts, $r, $rule, $item ) );
	}

	public function testGetItemsDiscountForBogoReturnsCorrectDiscountsWhenGetQuantityIsGreaterThanOne() {
		$id        = 0;
		$discounts = array(
			array(
				'discounts'      => array( 1 => 10.0 ),
				'original_price' => array( 1 => 100.0 ),
			),
		);
		$r         = 1;
		$rule      = array( 'get_quantity' => 2 );
		$item      = array(
			'line_subtotal' => 100.0,
			'quantity'      => 5,
		);

		codecept_debug( $discounts );

		$result = $this->get_items_discount_for_bogo( $id, $discounts, $r, $rule, $item );
		codecept_debug( $result );
		$this->assertEquals( 20.0, $result['discounts'][1] );
		$this->assertEquals( 18.0, $result['prices'][1] );
		$this->assertEquals( '2 x <del><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#36;</span>1.00</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#36;</span>18.00</bdi></span></ins><br/>3 x <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#36;</span>1.00</bdi></span></ins>', $result['bogo_qty'][1] );
	}

	// TODO: Add test for prepare_item_discounts

	public function testisBogoReturnsTrueWhenCampaignHasBuyXGetXIntent() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'BuyXGetX' );
		$result = $this->is_bogo( $campaign );
		$this->assertTrue( $result );
	}

	public function testisBogoReturnsTrueWhenCampaignHasBuyXGetYIntent() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'BuyXGetY' );
		$result = $this->is_bogo( $campaign );
		$this->assertTrue( $result );
	}

	public function testisBogoReturnsFalseWhenCampaignHasDifferentIntent() {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_discount_intent' )->willReturn( 'DifferentIntent' );
		$result = $this->is_bogo( $campaign );
		$this->assertFalse( $result );
	}

	public function testCartIsValidReturnsFalseWhenCartIsNotSet(): void {
		unset( WC()->cart );
		$result = $this->cart_is_valid();
		$this->assertFalse( $result );
	}

	public function testCartIsValidReturnsFalseWhenCartIsEmpty(): void {
		$cart = $this->createMock( \WC_Cart::class );
		$cart->method( 'is_empty' )->willReturn( true );
		WC()->cart = $cart;
		$result    = $this->cart_is_valid();
		$this->assertFalse( $result );
	}

	public function testCartIsValidReturnsTrueWhenCartIsNotEmpty(): void {
		$cart = $this->createMock( \WC_Cart::class );
		$cart->method( 'is_empty' )->willReturn( false );
		WC()->cart = $cart;
		$result    = $this->cart_is_valid();
		$this->assertTrue( $result );
	}

	public function testMinMaxAverageReturnsZEROWhenNumbersAreEmpty(): void {
		$result = $this->min_max_average( array() );
		$this->assertEquals( 0.00, $result );
	}

	public function testMinMaxAverageReturnsMinWhenCalculationTypeIsMin(): void {
		Settings::set( 'min_max_discount_amount', 'min' );
		$result = $this->min_max_average( array( 1, 2, 3 ) );
		$this->assertEquals( 1, $result );
	}

	public function testMinMaxAverageReturnsMaxWhenCalculationTypeIsMax(): void {
		Settings::set( 'min_max_discount_amount', 'max' );
		$result = $this->min_max_average( array( 1, 2, 3 ) );
		$this->assertEquals( 3, $result );
	}

	public function testMinMaxAverageReturnsAverageWhenCalculationTypeIsAverage(): void {
		Settings::set( 'min_max_discount_amount', 'average' );
		$result = $this->min_max_average( array( 1, 2, 3 ) );
		$this->assertEquals( 2, $result );
	}

	public function testMinMaxAverageReturnsMinWhenCalculationTypeIsInvalid(): void {
		Settings::set( 'min_max_discount_amount', 'invalid' );
		$result = $this->min_max_average( array( 1, 2, 3 ) );
		$this->assertEquals( 1, $result );
	}

	// public function testMergeDiscountsWithNoCurrentDiscounts(): void {
	// $current_discounts = array();
	// $new_discounts     = array( 'item1' => 10, 'item2' => 20 );
	// $result            = $this->merge_discounts( $current_discounts, $new_discounts );
	// $this->assertEquals( $new_discounts, $result );
	// }
	//
	// public function testMergeDiscountsWithExistingCurrentDiscounts(): void {
	// $current_discounts = array( 'item1' => 5, 'item2' => 15 );
	// $new_discounts     = array( 'item1' => 10, 'item2' => 20 );
	// $result            = $this->merge_discounts( $current_discounts, $new_discounts );
	// $this->assertEquals( $new_discounts, $result );
	// }
	//
	// public function testMergeDiscountsWithPartialOverlap(): void {
	// $current_discounts = array( 'item1' => 5, 'item3' => 15 );
	// $new_discounts     = array( 'item1' => 10, 'item2' => 20 );
	// $expected_result   = array( 'item1' => 10, 'item3' => 15, 'item2' => 20 );
	// $result            = $this->merge_discounts( $current_discounts, $new_discounts );
	// $this->assertEquals( $expected_result, $result );
	// }
	//
	// public function testMergeDiscountsWithNoOverlap(): void {
	// $current_discounts = array( 'item3' => 15, 'item4' => 25 );
	// $new_discounts     = array( 'item1' => 10, 'item2' => 20 );
	// $expected_result   = array( 'item3' => 15, 'item4' => 25, 'item1' => 10, 'item2' => 20 );
	// $result            = $this->merge_discounts( $current_discounts, $new_discounts );
	// $this->assertEquals( $expected_result, $result );
	// }

	public function testMergeDiscountDataWithEmptyCurrentData(): void {
		Settings::set( 'min_max_discount_amount', 'min' );
		$current_data = array();
		$new_data     = array(
			'prices'         => array( 10, 20 ),
			'discounts'      => array( 5, 10 ),
			'quantities'     => array( 1, 2 ),
			'get_ids'        => array( 1, 2 ),
			'intent'         => array( 'BuyXGetX', 'BuyXGetY' ),
			'labels'         => array( 'label1', 'label2' ),
			'offers'         => array( 'offer1', 'offer2' ),
			'bogo_qty'       => array( 1, 2 ),
			'original_price' => 30,
			'item_key'       => 'key1',
		);
		$result       = $this->merge_discount_data( $current_data, $new_data );
		$this->assertEquals( $new_data, $result );
	}

	public function testMergeDiscountDataWithNonEmptyCurrentData(): void {
		Settings::set( 'min_max_discount_amount', 'min' );
		$current_data    = array(
			'prices'         => array( 10, 20 ),
			'discounts'      => array( 5, 10 ),
			'quantities'     => array( 1, 2 ),
			'get_ids'        => array( 1, 2 ),
			'intent'         => array( 'BuyXGetX', 'BuyXGetY' ),
			'labels'         => array( 'label1', 'label2' ),
			'offers'         => array( 'offer1', 'offer2' ),
			'bogo_qty'       => array( 1, 2 ),
			'original_price' => 30,
			'item_key'       => 'key1',
		);
		$new_data        = array(
			'prices'         => array( 30, 40 ),
			'discounts'      => array( 15, 20 ),
			'quantities'     => array( 3, 4 ),
			'get_ids'        => array( 3, 4 ),
			'intent'         => array( 'Bulk', 'Bundle' ),
			'labels'         => array( 'label3', 'label4' ),
			'offers'         => array( 'offer3', 'offer4' ),
			'bogo_qty'       => array( 3, 4 ),
			'original_price' => 70,
			'item_key'       => 'key1',
		);
		$expected_result = array(
			'prices'         => array( 10, 20, 30, 40 ),
			'discounts'      => array( 5, 10, 15, 20 ),
			'quantities'     => array( 1, 2, 3, 4 ),
			'get_ids'        => array( 1, 2, 3, 4 ),
			'intent'         => array( 'BuyXGetX', 'BuyXGetY', 'Bulk', 'Bundle' ),
			'labels'         => array( 'label1', 'label2', 'label3', 'label4' ),
			'offers'         => array( 'offer1', 'offer2', 'offer3', 'offer4' ),
			'bogo_qty'       => array( 1, 2, 3, 4 ),
			'original_price' => 70,
			'item_key'       => 'key1',
			'final_price'    => 10,
			'key'            => 0,
		);
		$result          = $this->merge_discount_data( $current_data, $new_data );
		$this->assertEquals( $expected_result, $result );
	}

	public function testGetItemOffersReturnsCorrectLabelAndQuantityForFreeDiscountType(): void {
		$rule   = array(
			'min'            => 1,
			'max'            => 2,
			'discount_type'  => 'free',
			'discount_value' => 0,
			'get_quantity'   => 1,
		);
		$result = $this->get_item_offers( $rule );
		$this->assertEquals(
			array(
				'quantity' => 1,
				'label'    => 'Free',
			),
			$result
		);
	}

	public function testGetItemOffersReturnsCorrectLabelAndQuantityForPercentDiscountType(): void {
		$rule   = array(
			'min'            => 1,
			'max'            => 2,
			'discount_type'  => 'percent',
			'discount_value' => 20,
			'get_quantity'   => 1,
		);
		$result = $this->get_item_offers( $rule );
		$this->assertEquals(
			array(
				'quantity' => 1,
				'label'    => '20% off',
			),
			$result
		);
	}

	public function testGetItemOffersReturnsCorrectLabelAndQuantityForFixedDiscountType(): void {
		$rule   = array(
			'min'            => 1,
			'max'            => 2,
			'discount_type'  => 'fixed',
			'discount_value' => 10,
			'get_quantity'   => 1,
		);
		$result = $this->get_item_offers( $rule );
		$this->assertEquals(
			array(
				'quantity' => 1,
				'label'    => wc_price( 10 ) . ' off',
			),
			$result
		);
	}

	public function testGetItemOffersReturnsCorrectQuantityForNonEmptyMax(): void {
		$rule   = array(
			'min'            => 1,
			'max'            => 2,
			'discount_type'  => 'fixed',
			'discount_value' => 10,
			'get_quantity'   => '',
		);
		$result = $this->get_item_offers( $rule );
		$this->assertEquals( '1 - 2', $result['quantity'] );
	}

	public function testGetItemOffersReturnsCorrectQuantityForNonEmptyGetQuantity(): void {
		$rule   = array(
			'min'            => 1,
			'max'            => 2,
			'discount_type'  => 'fixed',
			'discount_value' => 10,
			'get_quantity'   => 3,
		);
		$result = $this->get_item_offers( $rule );
		$this->assertEquals( 3, $result['quantity'] );
	}

	public function testVerifyBulkRuleReturnsTrueWhenBasisIsWithinRange(): void {
		$basis  = 5;
		$rule   = array(
			'min' => 1,
			'max' => 10,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBulkRuleReturnsFalseWhenBasisIsBelowRange(): void {
		$basis  = 0;
		$rule   = array(
			'min' => 1,
			'max' => 10,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBulkRuleReturnsFalseWhenBasisIsAboveRange(): void {
		$basis  = 11;
		$rule   = array(
			'min' => 1,
			'max' => 10,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBulkRuleReturnsTrueWhenBasisIsEqualToMinRange(): void {
		$basis  = 1;
		$rule   = array(
			'min' => 1,
			'max' => 10,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBulkRuleReturnsTrueWhenBasisIsEqualToMaxRange(): void {
		$basis  = 10;
		$rule   = array(
			'min' => 1,
			'max' => 10,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBulkRuleReturnsTrueWhenBasisIsEqualToMinAndMaxRange(): void {
		$basis  = 1;
		$rule   = array(
			'min' => 1,
			'max' => 1,
		);
		$result = $this->verify_bulk_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBundleRuleReturnsFalseWhenRecursiveAndBasisIsNotMultiple(): void {
		$basis  = 5;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'yes',
		);
		$result = $this->verify_bundle_rule( $basis, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBundleRuleReturnsTrueWhenNotRecursiveAndBasisEqualsRuleBasis(): void {
		$basis  = 2;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'no',
		);
		$result = $this->verify_bundle_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBundleRuleReturnsFalseWhenNotRecursiveAndBasisNotEqualsRuleBasis(): void {
		$basis  = 3;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'no',
		);
		$result = $this->verify_bundle_rule( $basis, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBundleRuleReturnsTrueWhenRecursiveAndBasisIsMultiple(): void {
		$basis  = 4;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'yes',
		);
		$result = $this->verify_bundle_rule( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBuyXGetXRuleReturnsFalseWhenRecursiveAndBasisIsNotMultiple(): void {
		$basis  = 5;
		$item   = array( 'quantity' => 2 );
		$rule   = array(
			'min'          => 2,
			'max'          => 3,
			'recursive'    => 'yes',
			'get_quantity' => 1,
		);
		$result = $this->verify_buyxgetx_rule( $basis, $item, $rule );
		$this->assertFalse( $result );
	}

	// public function testVerifyBuyXGetXRuleReturnsFalseWhenItemQuantityIsLessThanOrEqualToGetQuantity(): void {
	// $basis  = 2;
	// $item   = array( 'quantity' => 1 );
	// $rule   = array( 'min' => 2, 'recursive' => 'no', 'get_quantity' => 2 );
	// $result = $this->verify_buyxgetx_rule( $basis, $item, $rule );
	// $this->assertFalse( $result );
	// }

	public function testVerifyBuyXGetXRuleReturnsTrueWhenBasisIsWithinRange(): void {
		$basis  = 2;
		$item   = array( 'quantity' => 3 );
		$rule   = array(
			'min'          => 2,
			'max'          => 3,
			'recursive'    => 'no',
			'get_quantity' => 1,
		);
		$result = $this->verify_buyxgetx_rule( $basis, $item, $rule );
		$this->assertTrue( $result );
	}

	public function testVerifyBuyXGetXRuleReturnsFalseWhenBasisIsBelowRange(): void {
		$basis  = 1;
		$item   = array( 'quantity' => 3 );
		$rule   = array(
			'min'          => 2,
			'max'          => 3,
			'recursive'    => 'no',
			'get_quantity' => 1,
		);
		$result = $this->verify_buyxgetx_rule( $basis, $item, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBuyXGetXRuleReturnsFalseWhenBasisIsAboveRange(): void {
		$basis  = 4;
		$item   = array( 'quantity' => 3 );
		$rule   = array(
			'min'          => 2,
			'max'          => 3,
			'recursive'    => 'no',
			'get_quantity' => 1,
		);
		$result = $this->verify_buyxgetx_rule( $basis, $item, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBuyXGetYRuleReturnsFalseWhenRuleGetIdsIsNotArray(): void {
		$this->campaign = (object) array( 'bogo_type' => 'categories' );
		$basis          = 5;
		$item           = array( 'quantity' => 2 );
		$rule           = array(
			'get_ids'      => 'not an array',
			'get_quantity' => 1,
		);
		$result         = $this->verify_buyxgety_rule( $this->campaign, $basis, $item, $rule );
		$this->assertFalse( $result );
	}


	public function testVerifyBuyXGetYRuleReturnsFalseWhenCampaignIsProductsAndProductIdNotInGetIds(): void {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_bogo_type' )->willReturn( 'products' );
		$basis  = 2;
		$item   = array(
			'product_id'   => 3,
			'quantity'     => 3,
			'variation_id' => 0,
		);
		$rule   = array(
			'get_ids'      => array( 1, 2 ),
			'get_quantity' => 1,
		);
		$result = $this->verify_buyxgety_rule( $campaign, $basis, $item, $rule );
		$this->assertFalse( $result );
	}

	public function testVerifyBuyXGetYRuleReturnsFalseWhenCampaignIsCategoriesAndProductNotInCategory(): void {
		$campaign = $this->createMock( Config::class );
		$campaign->method( 'get_bogo_type' )->willReturn( 'categories' );
		$basis       = 2;
		$item        = array(
			'product_id'   => 3,
			'quantity'     => 3,
			'variation_id' => 0,
		);
		$rule        = array(
			'get_ids'      => array( 1, 2 ),
			'get_quantity' => 1,
		);
		$dummyHelper = $this->createMock( DummyHelper::class );
		$dummyHelper->method( 'is_in_category' )->willReturn( true );
		$result = $this->verify_buyxgety_rule( $campaign, $basis, $item, $rule );
		$this->assertFalse( $result );
	}

	// public function testVerifyBuyXGetYRuleReturnsTrueWhenAllConditionsAreMet(): void {
	// $this->campaign = (object) array( 'bogo_type' => 'categories' );
	// $basis          = 2;
	// $item           = array( 'product_id' => 3, 'quantity' => 3,'variation_id'=>0 );
	// $rule           = array( 'get_ids' => array( 1, 2, 3 ), 'get_quantity' => 1 );
	// $dummyHelper    = $this->createMock( DummyHelper::class );
	// $dummyHelper->method( 'is_in_category' )->willReturn( true );
	// $dummyHelper->method( 'check_recursive_and_basis' )->willReturn( true );
	// $result = $this->verify_buyxgety_rule( $basis, $item, $rule );
	// $this->assertTrue( $result );
	// }

	public function testCheckRecursiveAndBasisReturnsTrueWhenNoMaxAndRecursiveAndBasisIsNotMultiple(): void {
		$basis  = 5;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'yes',
		);
		$result = $this->check_recursive_and_basis( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testCheckRecursiveAndBasisReturnsTrueWhenNoMaxAndNotRecursiveAndBasisNotEqualsRuleBasis(): void {
		$basis  = 3;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'no',
		);
		$result = $this->check_recursive_and_basis( $basis, $rule );
		$this->assertTrue( $result );
	}

	public function testCheckRecursiveAndBasisReturnsFalseWhenNoMaxAndNotRecursiveAndBasisEqualsRuleBasis(): void {
		$basis  = 2;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'no',
		);
		$result = $this->check_recursive_and_basis( $basis, $rule );
		$this->assertFalse( $result );
	}

	public function testCheckRecursiveAndBasisReturnsFalseWhenNoMaxAndRecursiveAndBasisIsMultiple(): void {
		$basis  = 4;
		$rule   = array(
			'min'       => 2,
			'recursive' => 'yes',
		);
		$result = $this->check_recursive_and_basis( $basis, $rule );
		$this->assertFalse( $result );
	}

	// public function testCheckRecursiveAndBasisReturnsFalseWhenMaxIsSet(): void {
	// $basis  = 5;
	// $rule   = array( 'min' => 2, 'max' => 10, 'recursive' => 'yes' );
	// $result = $this->check_recursive_and_basis( $basis, $rule );
	// $this->assertFalse( $result );
	// }
}

class DummyHelper {
	use IntentHelper;
}
