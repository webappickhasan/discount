<?php

namespace rest;

use Disco\App\Utility\Settings;
use Disco\Rest\Api;
use WP_Session_Tokens;

class SettingsApiTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	private $request_get;
	private $request_post;
	private $request_put;
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

		// Register API
		new Api();
		// Your set up methods here.

		// Set up a REST server instance.
		global $wp_rest_server;

		$this->server = $wp_rest_server = new \WP_REST_Server();
		do_action( 'rest_api_init' );

		// Helper.
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::SETTINGS_ROUTE_NAME;

		$this->request_get  = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request_post = new \WP_REST_Request( 'POST', $this->route_name );
		$this->request_put  = new \WP_REST_Request( 'PUT', $this->route_name );
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

	public function testGetRequestWasSuccessful() {
		$this->request_get->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request_get->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$response = $this->server->dispatch( $this->request_get );
		$data     = $response->get_data();

		codecept_debug( $data );

		$this->assertEquals( $response->status, 200 );
		/** @var \PHPUnit\Framework\int $valueCount */
		$valueCount = count( Settings::get() );
		$this->assertCount( $valueCount, $data );
	}

	public function testPostRequestWasSuccessful() {
		$this->request_post->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request_post->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request_post->set_body(
			json_encode(
				array(
					'product_price_type'      => 'price',
					'min_max_discount_amount' => 'min',
				)
			)
		);

		$response = $this->server->dispatch( $this->request_post );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$settings = Settings::Get();
		$this->assertEqualSets( $data, $settings );
	}

	public function testPutRequestWasSuccessful() {
		$this->request_put->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request_put->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request_put->set_body(
			json_encode(
				array(
					'min_max_discount_amount' => 'max',
				)
			)
		);

		$response = $this->server->dispatch( $this->request_put );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
		$settings = Settings::Get();
		$this->assertEqualSets( $data, $settings );
		$this->assertEquals( $settings['min_max_discount_amount'], $data['min_max_discount_amount'] );
	}
}
