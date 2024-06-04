<?php

namespace Disco\Tests\WPUnit\App\Utility;

use Disco\App\Utility\Settings;

class SettingsTest extends \Codeception\TestCase\WPTestCase {
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

	public function test_get_settings_with_key_all() {
		$settings = Settings::Get( 'all' );
		$this->assertIsArray( $settings );
	}

	public function test_get_settings_with_key_default() {
		$settings = Settings::Get( 'default' );
		$this->assertIsArray( $settings );
	}

	public function test_get_settings_with_key_not_found() {
		$settings = Settings::Get( 'aa' );
		$this->assertWPError( $settings );
		$this->assertEquals( 'Sorry, Invalid Settings Key.', $settings->get_error_message() );
	}

	public function test_get_settings_with_key_found() {
		$settings = Settings::Get( 'product_price_type' );
		$this->assertIsString( $settings );
	}

	public function test_set_setting() {
		Settings::Set( 'product_price_type', 'regular_price' );
		$getSettings = Settings::Get( 'product_price_type' );
		$this->assertEquals( 'regular_price', $getSettings );
	}

	public function test_set_setting_with_key_not_found() {
		$setSettings = Settings::Set( 'settings_2000', '200' );
		$getSettings = Settings::Get( 'settings_2000' );
		$this->assertWPError( $setSettings );
		$this->assertWPError( $getSettings );
	}

	public function test_set_setting_with_key_found() {
		$getSettings = Settings::Get( 'min_max_discount_amount' );
		$this->assertIsString( $getSettings );
	}

	public function testSaveSettingsWithEmptyArgs() {
		$result = Settings::save( array() );
		$this->assertTrue( $result );
	}

	public function testSaveSettingsWithExistingKey() {
		Settings::set( 'product_price_type', 'regular_price' );
		$result = Settings::save( array( 'product_price_type' => 'sale_price' ) );
		$this->assertTrue( $result );
		$this->assertEquals( 'sale_price', Settings::get( 'product_price_type' ) );
	}

	public function testSaveSettingsWithNonExistingKey() {
		$result = Settings::save( array( 'non_existing_key' => 'value' ) );
		$this->assertTrue( $result );
		$this->assertEquals( 'value', Settings::get( 'non_existing_key' ) );
	}

	public function testSaveSettingsWithMultipleKeys() {
		$result = Settings::save(
			array(
				'key1' => 'value1',
				'key2' => 'value2',
			)
		);
		$this->assertTrue( $result );
		$this->assertEquals( 'value1', Settings::get( 'key1' ) );
		$this->assertEquals( 'value2', Settings::get( 'key2' ) );
	}

	public function testGetPriceReturnsRegularPriceWhenSettingIsRegularPrice(): void {
		$product = $this->createMock( \WC_Product::class );
		$product->method( 'get_regular_price' )->willReturn( 100.0 );
		Settings::set( 'product_price_type', 'regular_price' );
		$result = Settings::get_price( $product );
		$this->assertEquals( 100.0, $result );
	}

	public function testGetPriceReturnsSalePriceWhenSettingIsSalePrice(): void {
		$product = $this->createMock( \WC_Product::class );
		$product->method( 'get_sale_price' )->willReturn( 80.0 );
		Settings::set( 'product_price_type', 'sale_price' );
		$result = Settings::get_price( $product );
		$this->assertEquals( 80.0, $result );
	}

	public function testGetPriceReturnsPriceWhenSettingIsPrice(): void {
		$product = $this->createMock( \WC_Product::class );
		$product->method( 'get_price' )->willReturn( 90.0 );
		Settings::set( 'product_price_type', 'price' );
		$result = Settings::get_price( $product );
		$this->assertEquals( 90.0, $result );
	}

	public function testGetPriceReturnsPriceWhenSettingIsInvalid(): void {
		$product = $this->createMock( \WC_Product::class );
		$product->method( 'get_price' )->willReturn( 90.0 );
		Settings::set( 'product_price_type', 'invalid' );
		$result = Settings::get_price( $product );
		$this->assertEquals( 90.0, $result );
	}
}
