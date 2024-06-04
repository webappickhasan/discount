<?php

use Codeception\Util\HttpCode;

class SearchAPICest {
	public function _before( FunctionalTester $I ) {
		$I->am( 'user' );
	}

	/**
	 * @test
	 * it should access search/product endpoint with parameters search
	 */
	public function it_should_access_search_product_endpoint_with_parameter_search( FunctionalTester $I ) {

		// Send a GET request to the endpoint
		$I->sendGET( 'search/product', array( 'search' => 'shirt' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/product', array( 'sesdarch' => 'shirt' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * write test for each below endpoints by following above test
	 *      search/category
	 *      search/tag
	 *      search/coupon
	 *      search/customer
	 *      search/state
	 *      search/attribute
	 */
	public function it_should_access_search_category_endpoint_with_parameter_search( FunctionalTester $I ) {

		// Send a GET request to the endpoint
		$I->sendGET( 'search/category', array( 'search' => 'Acc' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/category', array( 'search' => 'xxxxx' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * it should access search/tag endpoint with parameters search
	 */
	public function it_should_access_search_tag_endpoint_with_parameter_search( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'search/tag', array( 'search' => 'tag' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/tag', array( 'search' => 'aaaa' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * it should access search/coupon endpoint with parameters search
	 */
	public function it_should_access_search_coupon_endpoint_with_parameter_search( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'search/coupon', array( 'search' => 'sd' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/coupon', array( 'search' => 'aaaa' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * it should access search/customer endpoint with parameters search
	 */
	public function it_should_access_search_customer_endpoint_with_parameter_search( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'search/customer', array( 'search' => 'Ni' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/customer', array( 'search' => 'aaaa' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * it should access search/state endpoint with parameters search
	 */
	public function it_should_access_search_state_endpoint_with_parameter_search( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'search/state', array( 'search' => 'US' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/state', array( 'search' => 'aaaa' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}

	/**
	 * @test
	 * it should access search/attribute endpoint with parameters search
	 */
	public function it_should_access_search_attribute_endpoint_with_parameter_search( FunctionalTester $I ) {
		// Send a GET request to the endpoint
		$I->sendGET( 'search/attribute', array( 'search' => 'color' ) );

		// Check the status code
		$I->seeResponseCodeIs( HttpCode::OK ); // 200

		// The response should be a JSON
		$searchResponse = $I->grabResponse();
		$I->assertJson( $searchResponse );

		// Send a GET request to the endpoint
		$I->sendGET( 'search/attribute', array( 'search' => 'aaaa' ) );
		// Check the status code
		$I->seeResponseCodeIs( 404 ); // 404
	}
}
