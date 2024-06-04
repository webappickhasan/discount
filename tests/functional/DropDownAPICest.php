<?php

use Codeception\Util\HttpCode;

class DropDownAPICest {
	public function _before( FunctionalTester $I ) {
		$I->am( 'user' );
	}

	/**
	 * @test
	 * it should access custom endpoint with parameters
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_conditions( FunctionalTester $I ) {

		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'conditions' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$conditionsResponse = $I->grabResponse();
		$I->assertJson( $conditionsResponse );

		// Decode the JSON response and make sure it has the expected structure
		$conditionsResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $conditionsResponse['name'], 'conditions' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $conditionsResponse );
		$I->assertCount( 14, $conditionsResponse['values'] );

		// Check that the key and their values are correct
		$conditions = array(
			'include'       => 'Include',
			'exclude'       => 'Exclude',
			'contain'       => 'Contain',
			'not_contain'   => 'Does Not Contain',
			'equal'         => 'Equal',
			'not_equal'     => 'Not Equal',
			'greater'       => 'Greater Than',
			'greater_equal' => 'Greater Than or Equal',
			'lesser'        => 'Less Than',
			'lesser_equal'  => 'Less Than or Equal',
			'between'       => 'Between',
			'date_between'  => 'Date Between',
			'within_past'   => 'Within Past',
			'earlier_than'  => 'Earlier Than',
		);

		foreach ( $conditions as $key => $value ) {
			$I->assertEquals( $conditionsResponse['values'][ $key ], $value );
			$I->assertArrayHasKey( $key, $conditionsResponse['values'] );
		}
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters discount_types
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_discount_types( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'discount_types' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$discountTypesResponse = $I->grabResponse();
		$I->assertJson( $discountTypesResponse );

		// Decode the JSON response and make sure it has the expected structure
		$discountTypesResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $discountTypesResponse['name'], 'discount_types' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $discountTypesResponse );
		$I->assertCount( 7, $discountTypesResponse['values'] );

		// Check that the key and their values are correct
		$discountTypes = array(
			'Product'  => 'Products',
			'Cart'     => 'Cart',
			'Shipping' => 'Free Shipping',
			'Bulk'     => 'Bulk Discount',
			'Bundle'   => 'Bundle Discount',
			'BuyXGetX' => 'Buy X Get X',
			'BuyXGetY' => 'Buy X Get Y',
		);

		foreach ( $discountTypes as $key => $value ) {
			$I->assertEquals( $discountTypesResponse['values'][ $key ], $value );
			$I->assertArrayHasKey( $key, $discountTypesResponse['values'] );
		}
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters order_statuses
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_order_statuses( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'order_status' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$orderStatusesResponse = $I->grabResponse();
		$I->assertJson( $orderStatusesResponse );

		// Decode the JSON response and make sure it has the expected structure
		$orderStatusesResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $orderStatusesResponse['name'], 'order_status' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $orderStatusesResponse );
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters user_roles
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_user_roles( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'user_roles' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$userRolesResponse = $I->grabResponse();
		$I->assertJson( $userRolesResponse );

		// Decode the JSON response and make sure it has the expected structure
		$userRolesResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $userRolesResponse['name'], 'user_roles' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $userRolesResponse );
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters payment_methods
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_payment_methods( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'payment_methods' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$paymentMethodsResponse = $I->grabResponse();
		$I->assertJson( $paymentMethodsResponse );

		// Decode the JSON response and make sure it has the expected structure
		$paymentMethodsResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $paymentMethodsResponse['name'], 'payment_methods' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $paymentMethodsResponse );
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters countries
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_countries( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'countries' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$countriesResponse = $I->grabResponse();
		$I->assertJson( $countriesResponse );

		// Decode the JSON response and make sure it has the expected structure
		$countriesResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $countriesResponse['name'], 'countries' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $countriesResponse );
	}

	/**
	 * @test
	 * it should access dropdown endpoint with parameters filters
	 */
	public function it_should_access_dropdown_endpoint_with_parameter_filters( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'dropdown', array( 'search' => 'filters' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$filtersResponse = $I->grabResponse();
		$I->assertJson( $filtersResponse );

		// Decode the JSON response and make sure it has the expected structure
		$filtersResponse = json_decode( $I->grabResponse(), true );
		$I->assertEquals( $filtersResponse['name'], 'filters' );

		// Check that the values are present in the response
		$I->assertArrayHasKey( 'values', $filtersResponse );

		$I->assertCount( 6, $filtersResponse['values'] );

		// Count Product / Cart Item attributes
		$I->assertCount( 34, $filtersResponse['values'][0]['options'] );

		// Count Price attributes
		$I->assertCount( 9, $filtersResponse['values'][1]['options'] );

		// Count Cart attributes
		$I->assertCount( 6, $filtersResponse['values'][2]['options'] );

		// Count Product Purchase History attributes
		$I->assertCount( 4, $filtersResponse['values'][3]['options'] );

		// Count Customer attributes
		$I->assertCount( 8, $filtersResponse['values'][4]['options'] );

		// Count Customer Purchase History attributes
		$I->assertCount( 9, $filtersResponse['values'][5]['options'] );
	}
}
