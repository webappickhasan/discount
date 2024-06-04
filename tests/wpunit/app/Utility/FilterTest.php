<?php

namespace Disco\Tests\WPUnit\App\Utility;

use Disco\App\Utility\Config;
use Disco\App\Utility\Filter;
use stdClass;

// POWERLEVEL10K_SHORTEN_DIR_LENGTH=2
class FilterTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	protected $product;
	protected $config;

	/**
	 * @throws \WC_Data_Exception
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->product = $this->tester->create_simple_product( 1 );

		$campaign     = array(
			'id'              => '1',
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_rules'  => array(),
		);
		$this->config = new Config( $campaign );
		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests

	public function test_is_filter_passed_with_product_attributes() {

		$config = $this->config;

		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'title';
		$filter1->operator     = 'and';
		$filter1->condition    = 'contain';
		$filter1->compare      = 'Title';

		$filter2               = new stdClass();
		$filter2->id           = 600;
		$filter2->compare_with = 'sku';
		$filter2->operator     = 'and';
		$filter2->condition    = 'equal';
		$filter2->compare      = 'abcdef';

		$filter3               = new stdClass();
		$filter3->id           = 700;
		$filter3->compare_with = 'stock_status';
		$filter3->operator     = 'and';
		$filter3->condition    = 'in_list';
		$filter3->compare      = array( 'instock', 'outofstock' );

		$filter4               = new stdClass();
		$filter4->id           = 800;
		$filter4->compare_with = 'date_created';
		$filter4->operator     = 'and';
		$filter4->condition    = 'equal';
		$filter4->compare      = '2023-10-13';

		$filter5               = new stdClass();
		$filter5->id           = 900;
		$filter5->compare_with = 'id';
		$filter5->operator     = 'and';
		$filter5->condition    = 'between';
		$filter5->compare      = array( 1, 100 );

		$base_filter1                = new stdClass();
		$base_filter1->id            = 5000;
		$base_filter1->base_operator = 'and';
		$base_filter1->base_filters  = array( $filter1, $filter2 );

		$base_filter2                = new stdClass();
		$base_filter2->id            = 6000;
		$base_filter2->base_operator = 'and';
		$base_filter2->base_filters  = array( $filter3 );

		$filters = array( $base_filter1 );

		$product = $this->product;
		$product->set_name( 'My Product Title' );
		$product->set_sku( 'abcdef' );
		$product->set_stock_status( 'instock' );
		$product->set_date_created( '2023-10-13' );
		$product->save();

		codecept_debug( $filters );
		codecept_debug( 'Title: ' . $this->product->get_name() );
		codecept_debug( 'SKU: ' . $this->product->get_sku() );
		codecept_debug( 'Stock Status: ' . $this->product->get_stock_status() );
		codecept_debug( 'Date Created: ' . $this->product->get_date_created()->date( 'Y-m-d' ) );

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );

		// Test Group Filter.
		$this->assertTrue( $getFilterStatus->check_group_conditions( $base_filter1->base_filters ) );

		// Test Base Filter.
		$this->assertTrue( $getFilterStatus->check_all_conditions( $filters ) );
		$this->assertTrue( $getFilterStatus->is_passed() );
	}

	public function test_compare_value() {

		$config                = $this->config;
		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'stock_status';
		$filter1->operator     = 'and';
		$filter1->condition    = 'in_list';
		$filter1->compare      = array( 'instock', 'outofstock' );

		$this->product->set_tag_ids( array( 1, 2 ) );
		$this->product->set_name( 'My Product Title' );
		$this->product->set_date_created( '2023-10-13' );
		$this->product->set_stock_status( 'instock' );
		$this->product->save();

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );

		// Test Array Compare
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
		// Test String Compare
		$filter1->compare_with = 'title';
		$filter1->condition    = 'contain';
		$filter1->compare      = 'Title';
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
		// Test Number Compare
		$filter1->compare_with = 'id';
		$filter1->condition    = 'equal';
		$filter1->compare      = $this->product->get_id();
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
		// Test Date Compare
		$filter1->compare_with = 'date_created';
		$filter1->condition    = 'equal';
		$filter1->compare      = '2023-10-13';
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
	}

	public function test_string_compare() {
		$config                = $this->config;
		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'title';
		$filter1->operator     = 'and';
		$filter1->condition    = 'contain';
		$filter1->compare      = 'Title';

		$this->product->set_name( 'My Product Title' );
		$this->product->save();

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );

		// Test String Equal Compare
		$filter1->condition = 'equal';
		$filter1->compare   = 'My Product Title';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
		// Test String Doesn't Equally Compare
		$filter1->condition = 'not_equal';
		$filter1->compare   = 'Title';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
		// Test String Contain Compare
		$filter1->condition = 'contain';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
		// Test String Doesn't Contain Equally Compare
		$filter1->condition = 'not_contain';
		$filter1->compare   = 'ABCDEF';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
		// Test String Start With Compare
		$filter1->condition = 'start_with';
		$filter1->compare   = 'My';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
		// Test String End With Compare
		$filter1->condition = 'end_with';
		$filter1->compare   = 'Title';
		$this->assertTrue( $getFilterStatus->string_compare( $filter1 ) );
	}

	public function test_number_compare() {
		$config                = $this->config;
		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'id';
		$filter1->operator     = 'and';
		$filter1->condition    = 'equal';
		$filter1->compare      = $this->product->get_id();

		$this->product->set_name( 'My Product Title' );
		$this->product->save();

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );

		// Test Number Compare.
		// Test Number Equally Compare
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Doesn't Equally Compare
		$filter1->condition = 'not_equal';
		$filter1->compare   = 100;
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Greater Compare
		$filter1->condition = 'greater';
		$filter1->compare   = 0;
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Greater Equally Compare
		$filter1->condition = 'greater_equal';
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Lesser Compare
		$filter1->condition = 'lesser';
		$filter1->compare   = 1000;
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Lesser Equally Compare
		$filter1->condition = 'lesser_equal';
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
		// Test Number Between Compare
		$filter1->condition = 'between';
		$filter1->compare   = array( 0, 1000 );
		$this->assertTrue( $getFilterStatus->number_compare( $filter1 ) );
	}

	public function test_array_compare() {
		$config                = $this->config;
		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'stock_status';
		$filter1->operator     = 'and';
		$filter1->condition    = 'in_list';
		$filter1->compare      = array( 'instock', 'outofstock' );

		$this->product->set_tag_ids( array( 1, 2 ) );
		$this->product->save();

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );

		// Test Array In List Compare
		$this->assertTrue( $getFilterStatus->array_compare( $filter1 ) );
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
		// Test Array Between Compare
		$filter1->compare_with = 'tags';
		$filter1->compare      = array( 1, 2, 3, 4 );
		$this->assertTrue( $getFilterStatus->array_compare( $filter1 ) );
		// Test Array Not In List Compare
		$filter1->condition = 'not_in_list';
		$filter1->compare   = array( 3, 4, 5, 6 );
		$this->assertTrue( $getFilterStatus->array_compare( $filter1 ) );
	}

	public function test_date_compare() {
		$config                = $this->config;
		$filter1               = new stdClass();
		$filter1->id           = 500;
		$filter1->compare_with = 'date_created';
		$filter1->operator     = 'and';
		$filter1->condition    = 'equal';
		$filter1->compare      = '2023-10-13';

		$this->product->set_date_created( '2023-10-13' );
		$this->product->set_date_modified( '2023-10-14' );
		$this->product->save();

		$getFilterStatus = new Filter( $config, array( 'product' => $this->product ) );
		// Test Date Equal Compare
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		$this->assertTrue( $getFilterStatus->compare_value( $filter1 ) );
		// Test Date Not Equally Compare
		$filter1->condition = 'not_equal';
		$filter1->compare   = '2023-10-14';
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		// Test Date Greater Compare
		$filter1->condition = 'greater';
		$filter1->compare   = '2023-10-12';
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		// Test Date Greater Equally Compare
		$filter1->condition = 'greater_equal';
		$filter1->compare   = '2023-10-13';
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		// Test Date Lesser Compare
		$filter1->condition = 'lesser';
		$filter1->compare   = '2023-10-14';
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		// Test Date Lesser Equally Compare
		$filter1->condition = 'lesser_equal';
		$filter1->compare   = '2023-10-13';
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
		// Test Date Between Compare
		$filter1->condition = 'between';
		$filter1->compare   = array( '2023-10-12', '2023-10-14' );
		$this->assertTrue( $getFilterStatus->date_compare( $filter1 ) );
	}
}
