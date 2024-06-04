<?php

namespace Disco\Tests\WPUnit;

use Codeception\TestCase\WPTestCase;
use Disco\App\Campaign;
use Disco\App\Utility\Config;
use Disco\Backend\ActDeact;
use stdClass;

class CampaignTest extends WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	/**
	 * @var string
	 */
	protected $root_dir;

	public function setUp(): void {
		parent::setUp();
		ActDeact::create_plugin_table();
		// your set up methods here
		wp_cache_flush();
	}

	public function tearDown(): void {

		$campaigns = ( new Campaign() )->get_campaigns();
		foreach ( $campaigns as $campaign ) {
			( new Campaign() )->delete_campaign( $campaign->id );
		}

		parent::tearDown();
	}

	// Tests
	public function test_get_empty_campaigns() {
		// Start the timer
		$start       = microtime( true );
		$startMemory = memory_get_peak_usage();

		// Code to measure execution time and memory usage

		// Get campaigns from database.
		$campaigns = ( new Campaign() )->get_campaigns();

		// End the timer
		$end       = microtime( true );
		$endMemory = memory_get_peak_usage();

		// Calculate the execution time
		$executionTime = $end - $start;

		$executionTime = round( $executionTime * 1000 );

		// Calculate the memory usage
		$memoryUsage = $endMemory - $startMemory;

		$memoryUsage = $this->formatBytes( $memoryUsage );

		// Output the result
		$exTime   = "\nScript execution time: " . $executionTime . ' ms';
		$memUsage = "\nPeak memory usage: " . $memoryUsage;

		codecept_debug( $exTime );
		codecept_debug( $memUsage );

		$this->assertEqualSets( array(), $campaigns );
	}

	public function formatBytes( $size, $precision = 2 ) {
		$base     = log( $size, 1024 );
		$suffixes = array( '', 'K', 'M', 'G', 'T' );

		return round( pow( 1024, $base - floor( $base ) ), $precision ) . ' ' . $suffixes[ floor( $base ) ];
	}

	// q: how to Write test cases for the following methods: (new Campaign())->save_campaign(), (new Campaign())->get_campaigns(), (new Campaign())->get_campaign(), (new Campaign())->GetRow(), (new Campaign())->get_campaignsByStatus(), (new Campaign())->get_campaignsByType(), (new Campaign())->get_campaignsByStatusAndType(), (new Campaign())->get_campaignsByStatusAndTypeAndDate(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearch(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimit(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffset(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrder(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderBy(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParent(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentId(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSku(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitle(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitleAndParentDescription(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitleAndParentDescriptionAndParentShortDescription(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitleAndParentDescriptionAndParentShortDescriptionAndParentPrice(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitleAndParentDescriptionAndParentShortDescriptionAndParentPriceAndParentRegularPrice(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimitAndOffsetAndOrderAndOrderByAndParentAndParentIdAndParentSkuAndParentTitleAndParentDescriptionAndParentShortDescriptionAndParentPriceAndParentRegularPriceAndParentSalePrice(), (new Campaign())->get_campaignsByStatusAndTypeAndDateAndSearchAndLimit
	// a:

	public function test_save_campaign_to_database_by_Insert_method() {

		$this->tester->create_db_table();
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$save      = ( new Campaign() )->insert( $campaign );
		$save2     = ( new Campaign() )->insert( $campaign );
		$campaigns = ( new Campaign() )->get_campaigns();

		codecept_debug( $campaigns );

		$this->assertIsInt( $save );
		$this->assertGreaterThan( 0, $save );
		// $this->assertEquals(2,$save2);
		// $this->assertCount( 2, $campaigns);
	}

	public function test_save_campaign_by_SaveCampaign_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'priority'        => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$save = ( new Campaign() )->save_campaign( $campaign );

		codecept_debug( $save );

		$this->assertInstanceOf( Config::class, $save );
		$this->assertGreaterThan( 0, $save->id );
		$this->assertEquals( 'Test Product Intent Campaign with automated 10% discount', $save->name );
		$this->assertEqualSets( array( 'all' ), $save->products );

		$this->assertEquals( 'Products', $save->discount_intent );
		$this->assertEquals( 'automated', $save->discount_method );
		$this->assertEquals( 'percent', $save->discount_type );

		$S1       = new stdClass();
		$S1->id   = 500;
		$S1->name = 'Product 1';

		$S2       = new stdClass();
		$S2->id   = 600;
		$S2->name = 'Product 2';

		$product        = json_encode( array( $S1, $S2 ) );
		$save->products = $product;
		$this->assertEquals( array( 500, 600 ), $save->get_product_ids() );

		$this->assertEmpty( $save->discount_label );
		$this->assertEquals( 'Discount', $save->get_discount_label() );
	}

	public function test_get_a_campaign_from_database_by_get_row_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Product',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		$getCampaign = ( new Campaign() )->get_row( $campaign_id );

		$campaigns = ( new Campaign() )->get_campaigns();

		// codecept_debug($campaigns);

		$this->assertEquals( $campaign_id, $getCampaign->id );
	}

	public function test_get_a_campaign_from_database_by_get_campaign_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );

		$this->assertEquals( $campaign_id, $getCampaign->id );
	}

	public function test_update_a_campaign_from_database_by_update_campaign_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'priority'        => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );

		$updateCampaign = array(
			'id'              => $campaign_id,
			'name'            => 'Test Product Intent Campaign with automated 20% discount',
			'status'          => '1',
			'priority'        => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '20',
		);

		$update = ( new Campaign() )->update_campaign( $campaign_id, $updateCampaign );
		// codecept_debug( $update);
		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );

		$this->assertEquals( 'Test Product Intent Campaign with automated 20% discount', $getCampaign->name );
	}

	public function test_update_a_campaign_from_database_by_update_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'priority'        => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		$updateCampaign = array(
			'id'              => $campaign_id,
			'name'            => 'Test Product Intent Campaign with automated 20% discount',
			'status'          => '1',
			'priority'        => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '20',
		);

		( new Campaign() )->update( $updateCampaign, $campaign_id );

		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );

		// codecept_debug( $getCampaign);

		$this->assertEquals( $updateCampaign['name'], $getCampaign->name );
	}

	public function test_delete_a_campaign_from_database_by_delete_campaign_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		( new Campaign() )->delete_campaign( $campaign_id );

		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );

		$this->assertWPError( $getCampaign );
		$this->assertEquals( 'Sorry, Invalid campaign id.', $getCampaign->get_error_message() );
	}

	public function test_delete_a_campaign_from_database_by_delete_method() {
		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$campaign_id = ( new Campaign() )->insert( $campaign );

		$delete = ( new Campaign() )->delete( $campaign_id );
		$this->assertIsInt( $delete );

		$delete = ( new Campaign() )->delete( $campaign_id );
		$this->assertEquals( 0, $delete );

		$getCampaign = ( new Campaign() )->get_campaign( $campaign_id );
		$this->assertWPError( $getCampaign );
		$this->assertEquals( 'Sorry, Invalid campaign id.', $getCampaign->get_error_message() );
	}
}
