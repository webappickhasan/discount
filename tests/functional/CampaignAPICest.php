<?php

use Codeception\Util\HttpCode;

class CampaignAPICest {

	private $campaignId;

	private $campaignConfig = array(
		'option_name'          => 'DISCO_CAMPAIGN-64f6fdifjwf.84977949w79',
		'name'                 => 'Campaign Via Unit Test',
		'discount_intent'      => 'product', /* product, cart, free_shipping, bulk, bundle,buyXgetX, buyXgetY */
		'ui'                   => array(),
		'priority'             => '1',
		'status'               => '1',
		'created_by'           => '1',
		'created_date'         => '',
		'modified_by'          => '',
		'modified_date'        => '',
		'products'             => array( 'all' ),
		'filter'               => array(),
		'discount_type'        => 'percent',
		'discount_value'       => '10',
		'discount_label'       => '',
		'discount_valid_from'  => '2023-09-15T00:00:00',
		'discount_valid_to'    => '2023-09-19T00:00:00',
		'discount_method'      => 'sd',
		'discount_coupon'      => 'fsdf',
		'total_discount_limit' => '',
		'total_sales_limit'    => '',
	);

	public function _before( FunctionalTester $I ) {
	}

	// Test the campaign endpoint. API Code written in the rest/CampaignApi.php class and functional code  written in the app/Campaign.php class.

	//
	// **
	// * @test
	// * it should PUT request campaigns endpoint with parameters and update a campaign
	// */
	// public function it_should_PUT_request_campaigns_endpoint_with_parameters_and_update_a_campaign( FunctionalTester $I ) {
	//
	// CREATE CAMPAIGN
	// Send a POST request to the endpoint
	// $I->sendPOST( 'campaigns', $this->campaignConfig );
	//
	// $CreateCampaignResponse = json_decode( $I->grabResponse(), true );
	// $this->campaignId       = $CreateCampaignResponse['id'];
	//
	// UPDATE CAMPAIGN
	//
	// $this->campaignConfig['name'] = 'Update Campaign Via Unit Test';
	// Send a PUT request to the endpoint
	// $I->sendPUT( 'campaigns/' . $this->campaignId, $this->campaignConfig );
	//
	// Check the status code
	// $I->seeResponseCodeIs( HttpCode::OK ); // 200
	//
	// $UpdateCampaignResponse = json_decode( $I->grabResponse(), true );
	// $name                   = $UpdateCampaignResponse['name'];
	//
	// $I->assertEquals( $name, 'Update Campaign Via Unit Test' );
	//
	//
	// PATCH
	// $this->campaignConfig['name'] = 'Update PATCH Campaign Via Unit Test';
	// Create a patch request to update the campaign
	// $I->sendPATCH( 'campaigns/' . $this->campaignId, array(
	// 'name' => $this->campaignConfig['name'],
	// ) );
	//
	// Check the status code
	// $I->seeResponseCodeIs( HttpCode::OK ); // 200
	// Check the response
	// $UpdateCampaignResponse = json_decode( $I->grabResponse(), true );
	// $name                   = $UpdateCampaignResponse['name'];
	// $I->assertEquals( $name, 'Update PATCH Campaign Via Unit Test' );
	//
	// GET A CAMPAIGN
	//
	// Send a GET request to the endpoint
	// $I->sendGET( 'campaigns/' . $this->campaignId );
	// Check the status code
	// $I->seeResponseCodeIs( HttpCode::OK ); // 200
	// Check the response
	// $GetCampaignResponse = json_decode( $I->grabResponse(), true );
	// $name                = $GetCampaignResponse['name'];
	// $I->assertEquals( $name, 'Update PATCH Campaign Via Unit Test' );
	//
	// DELETE A CAMPAIGN
	// $I->sendDELETE( 'campaigns/' . $this->campaignId );
	// Check the status code
	// $I->seeResponseCodeIs( HttpCode::OK ); // 200
	// Check the response
	// $DeleteCampaignResponse = json_decode( $I->grabResponse(), true );
	// $DeleteCampaignResponse = $DeleteCampaignResponse['deleted'];
	// $I->assertTrue( $DeleteCampaignResponse );
	// }

	/**
	 * @test
	 * it should GET request campaigns endpoint and return all campaigns
	 */
	public function it_should_GET_request_campaigns_endpoint_and_return_all_campaigns( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'campaigns' );
		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		// Check the response
		$GetCampaignsResponse = json_decode( $I->grabResponse(), true );
		$I->assertIsArray( $GetCampaignsResponse );
	}

	/**
	 * @test
	 * it should POST request campaigns endpoint with parameters and create a campaign
	 */
	public function it_should_POST_request_campaigns_endpoint_with_parameters_and_create_a_campaign( FunctionalTester $I ) {
		// Send a POST request to the endpoint
		$I->sendPOST( 'campaigns', $this->campaignConfig );
		// Check the status code
		$I->seeResponseCodeIs( HttpCode::CREATED ); // 201
		// Check the response
		$CreateCampaignResponse = json_decode( $I->grabResponse(), true );
		$this->campaignId       = $CreateCampaignResponse['id'];
		$name                   = $CreateCampaignResponse['name'];
		$I->assertEquals( $name, 'Campaign Via Unit Test' );
	}

	/**
	 * @test
	 * it should GET request campaigns endpoint with id and return a campaign
	 */
	public function it_should_GET_request_campaigns_endpoint_with_id_and_return_a_campaign( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'campaigns/' . $this->campaignId );
		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		// Check the response
		$GetCampaignResponse = json_decode( $I->grabResponse(), true );
		$name                = $GetCampaignResponse['name'];
		$I->assertEquals( $name, 'Campaign Via Unit Test' );
	}

	/**
	 * @test
	 * it should UPDATE request campaigns endpoint with id and update a campaign
	 */
	public function it_should_UPDATE_request_campaigns_endpoint_with_id_and_delete_a_campaign( FunctionalTester $I ) {
		$this->campaignConfig['name'] = 'Update Campaign Via Unit Test';
		// Send a PUT request to the endpoint
		$I->sendPUT( 'campaigns/' . $this->campaignId, $this->campaignConfig );
		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		// Check the response
		$UpdateCampaignResponse = json_decode( $I->grabResponse(), true );
		$name                   = $UpdateCampaignResponse['name'];
		$I->assertEquals( $name, 'Update Campaign Via Unit Test' );
	}

	/**
	 * @test
	 * it should PATCH request campaigns endpoint with id and update a campaign
	 */
	public function it_should_PATCH_request_campaigns_endpoint_with_id_and_update_a_campaign( FunctionalTester $I ) {
		$this->campaignConfig['name'] = 'Update PATCH Campaign Via Unit Test';
		// Create a patch request to update the campaign
		$I->sendPATCH(
			'campaigns/' . $this->campaignId,
			array(
				'name' => $this->campaignConfig['name'],
			)
		);

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		// Check the response
		$UpdateCampaignResponse = json_decode( $I->grabResponse(), true );
		$name                   = $UpdateCampaignResponse['name'];
		$I->assertEquals( $name, 'Update PATCH Campaign Via Unit Test' );
	}

	/**
	 * @test
	 * it should DELETE request campaigns endpoint with id and delete a campaign
	 */
	public function it_should_DELETE_request_campaigns_endpoint_with_id_and_delete_a_campaign( FunctionalTester $I ) {
		$I->sendDELETE( 'campaigns/' . $this->campaignId );
		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		// Check the response
		$DeleteCampaignResponse = json_decode( $I->grabResponse(), true );
		$DeleteCampaignResponse = $DeleteCampaignResponse['deleted'];
		$I->assertTrue( $DeleteCampaignResponse );
	}
}
