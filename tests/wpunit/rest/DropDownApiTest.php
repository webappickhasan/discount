<?php

namespace Disco\Tests\WPUnit\Rest;

use Disco\App\Utility\DropDown;
use Disco\Rest\Api;
use WP_REST_Request;
use WP_Session_Tokens;

class DropDownApiTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	private $request;
	private $route_name;

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

		// Register API
		new Api();

		// Your set up methods here.
		// Set up a REST server instance.
		global $wp_rest_server;

		$this->server = $wp_rest_server = new \WP_REST_Server();
		do_action( 'rest_api_init' );

		// Helper.
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::DROPDOWN_ROUTE_NAME;

		$this->request = new WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
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
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( $this->route_name, $routes );
	}

	public function testRequestWasInvalid() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		// $this->request->set_body( '{"name":"Test Testovski", "email":"test.email@email.com", "message":"Sample Message", "recaptcha":"some_random_string"}' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( $data['code'], 'rest_dropdown_invalid_search_term' );
		$this->assertEquals( $data['data']['status'], 404 );
	}

	public function testRequestDropDownConditionsWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'conditions' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'conditions', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::conditions() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownDiscountIntentsWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'discount_intents' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'discount_intents', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::discount_intents() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownDiscountMethodsWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'discount_methods' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'discount_methods', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = 2;
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownDiscountTypesWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'discount_types' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'discount_types', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = 5;
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownProductsWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'products' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'products', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = 2;
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownOrderStatusWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'order_status' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		// codecept_debug($data);

		$this->assertEquals( 'order_status', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::order_status() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownUserRolesWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'user_roles' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'user_roles', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::user_roles() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownPaymentMethodsWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'payment_methods' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'payment_methods', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::payment_methods() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownCountriesWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'countries' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'countries', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::countries() );
		$this->assertCount( $valueCount, $data['values'] );
	}

	public function testRequestDropDownFiltersWasSuccessful() {
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		// Test conditions
		$this->request->set_param( 'search', 'filters' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 'filters', $data['name'] );
		$this->assertEquals( 200, $response->status );

		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( DropDown::filters() );
		$this->assertCount( $valueCount, $data['values'] );
	}
}
