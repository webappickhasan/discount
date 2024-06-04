<?php

namespace Disco\Tests\WPUnit\App\Utility;

use Disco\App\Utility\Config;
use stdClass;

class ConfigTest extends \Codeception\TestCase\WPTestCase {
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


	public function test_get_config() {

		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$config = new Config( $campaign );

		$this->assertEquals( $campaign['id'], $config->id );
	}

	public function test_set_config() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$config = new Config( $campaign );

		$config->name = 'Test Product Intent Campaign with automated 20% discount';

		$this->assertEquals( 'Test Product Intent Campaign with automated 20% discount', $config->name );
	}

	public function test_product_is_applicable() {

		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$config = new Config( $campaign );

		$this->assertTrue( $config->product_is_applicable( 1 ) );
	}

	public function test_get_discount_rules() {
		$default_rule = array(
			'id'             => '',
			'min'            => '',
			'max'            => '',
			'get_quantity'   => '', // Get quantity.
			'get_ids'        => array(), // Get discount.
		// 'discount_type'  => '',
			'discount_value' => '',
			'discount_label' => '',
			'recursive'      => 'no',
		);

		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_rules'  => '',
		);

		$config = new Config( $campaign );
		$this->assertFalse( $config->get_discount_rules() );

		$campaign['discount_rules'] = array( $default_rule );
		$config                     = new Config( $campaign );
		$this->assertIsArray( $config->get_discount_rules() );

		$this->assertEquals( 'percent', $config->get_discount_rules()[0]->discount_type );
	}

	public function test_get_discount_amount() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => '',
			'discount_value'  => '10',
		);
		$config   = new Config( $campaign );
		$this->assertEquals( '10', $config->discount_value );
	}

	public function test_get_discount_amount_formatted() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => '',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );
		$this->assertEquals( 10.00, $config->get_discount_value() );
	}

	public function test_get_discount_label() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => '',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );
		$this->assertEquals( false, $config->discount_label );
	}

	public function test_get_discount_label_formatted() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => '',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );
		$this->assertEquals( 'Discount', $config->get_discount_label() );
	}

	public function test_get_product_ids() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'discount_method' => 'automated',
			'discount_type'   => '',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );

		$this->assertEquals( 'all', $config->get_product_ids() );

		$S1       = new stdClass();
		$S1->id   = 500;
		$S1->name = 'Product 1';

		$S2       = new stdClass();
		$S2->id   = 600;
		$S2->name = 'Product 2';

		$product          = json_encode( array( $S1, $S2 ) );
		$config->products = $product;
		$this->assertEquals( array( 500, 600 ), $config->get_product_ids() );

		$config->products = array( 'all' );
		$this->assertEquals( 'all', $config->get_product_ids() );
	}

	public function test_get_wc_discount_types() {
		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );

		$config->discount_type = 'percent_per_product';
		$this->assertEquals( 'percent', $config->get_wc_discount_type() );

		$config->discount_type = 'fixed_per_product';
		$this->assertEquals( 'fixed_product', $config->get_wc_discount_type() );

		$config->discount_type = 'fixed';
		$this->assertEquals( 'fixed_cart', $config->get_wc_discount_type() );

		$config->discount_type = 'percent';
		$this->assertEquals( 'percent', $config->get_wc_discount_type() );

		$config->discount_type = '';
		$this->assertEquals( 'fixed_cart', $config->get_wc_discount_type() );
	}

	public function test_get_filters() {

		$campaign = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => 10,
		);
		$config   = new Config( $campaign );

		$S1            = new stdClass();
		$S1->id        = 500;
		$S1->filter    = 'id';
		$S1->operator  = 'and';
		$S1->condition = 'not_equal';
		$S1->compare   = '';

		$S2            = new stdClass();
		$S2->id        = 500;
		$S2->filter    = 'title';
		$S2->operator  = 'and';
		$S2->condition = 'contain';
		$S2->compare   = 'Vneck';

		$product            = json_encode( array( $S1, $S2 ) );
		$config->conditions = $product;
		$this->assertEquals( array( $S1, $S2 ), $config->get_conditions() );
	}
}
