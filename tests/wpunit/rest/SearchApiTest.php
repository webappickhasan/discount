<?php

namespace rest;

use Disco\Rest\Api;
use WP_Session_Tokens;

class SearchApiTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	private $request;
	private $route_name;
	private $token;
	private $manager;

	public function setUp(): void {
		// Before...
		parent::setUp();
		// Create user
		$user_id = $this->factory->user->create();
		$user    = $this->factory()->user->get_object_by_id( $user_id );
		$user->set_role( 'administrator' );
		wp_set_current_user( $user_id );

		// Set User Cookies
		$expiration    = time() + DAY_IN_SECONDS;
		$this->manager = WP_Session_Tokens::get_instance( $user_id );
		$this->token   = $this->manager->create( $expiration );
		$this->manager->verify( $this->token );

		// Your set up methods here.
		new Api();
		// Set up a REST server instance.
		global $wp_rest_server;

		$this->server = $wp_rest_server = new \WP_REST_Server();
		do_action( 'rest_api_init' );

		// Helper.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Destroy the user.
		$this->manager->destroy( $this->token );

		// Test cleanup.
		global $wp_rest_server;
		$wp_rest_server = null;
		// Then...
		parent::tearDown();
	}

	// Tests
	public function testRouteIsRegistered() {
		$this->route_name   = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME;
		$routes             = $this->server->get_routes();
		$product_route_name = $this->route_name . '/product';
		$this->assertArrayHasKey( $product_route_name, $routes );

		$category_route_name = $this->route_name . '/category';
		$this->assertArrayHasKey( $category_route_name, $routes );

		$tag_route_name = $this->route_name . '/tag';
		$this->assertArrayHasKey( $tag_route_name, $routes );

		$attribute_route_name = $this->route_name . '/attribute';
		$this->assertArrayHasKey( $attribute_route_name, $routes );

		$coupon_route_name = $this->route_name . '/coupon';
		$this->assertArrayHasKey( $coupon_route_name, $routes );

		$customer_route_name = $this->route_name . '/customer';
		$this->assertArrayHasKey( $customer_route_name, $routes );

		$state_route_name = $this->route_name . '/state';
		$this->assertArrayHasKey( $state_route_name, $routes );
	}

	public function testRequestWasInvalid() {
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/prod'; // invalid route
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'test_product' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'rest_no_route', $data['code'] );
		$this->assertEquals( 'No route was found matching the URL and request method.', $data['message'] );
	}

	/**
	 * @throws \WC_Data_Exception
	 */
	public function testProductSearchRequestWasSuccessful() {

		$product = $this->tester->create_simple_product( 1 );

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/product';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'Simple' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( $data[0]['id'], $product->get_id() );
	}

	public function testCategorySearchRequestWasSuccessful() {

		$categories = $this->tester->create_categories();

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/category';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'Category' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( 'Category 1', $data[0]['name'] );
	}

	public function testTagSearchRequestWasSuccessful() {

		$tags = $this->tester->create_tags();

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/tag';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'Tag' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( 'Tag 1', $data[0]['name'] );
	}

	public function testAttributeSearchRequestWasSuccessful() {

		$attributes = $this->tester->create_attributes();

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/attribute';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'col' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( 'Color', $data[0]['name'] );
	}

	public function testCouponSearchRequestWasSuccessful() {

		$coupon = $this->tester->create_coupon( 'ABCDEF' );

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/coupon';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'ABC' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();
		// codecept_debug($data);
		$this->assertEquals( 200, $response->status );
		$this->assertEquals( $coupon->get_code(), $data[0]['name'] );
	}

	public function testCustomerSearchRequestWasSuccessful() {

		$customer = $this->tester->create_customer();

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/customer';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', $customer->get_first_name() ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$expected = $customer->get_first_name() . ' ' . $customer->get_last_name() . ' (' . $customer->get_email() . ')';
		$this->assertEquals( $expected, $data[0]['name'] );
	}

	public function testStateSearchRequestWasSuccessful() {

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SEARCH_ROUTE_NAME . '/state';
		$this->request    = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_param( 'search', 'US' ); // valid search term

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( 'Alabama', $data[0]['name'] );
	}
}
