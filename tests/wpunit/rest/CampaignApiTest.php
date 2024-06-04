<?php

namespace rest;

use Disco\App\Campaign;
use Disco\App\Utility\Config;
use Disco\Rest\Api;
use WP_Session_Tokens;

class CampaignApiTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	private $request;
	private $route_name;

	private $campaign;

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
		// Your set-up methods here.
		new Api();

		$campaign = array(
			'name'            => 'Test Product Intent Campaign with automated 10% discount',
			'status'          => '1',
			'discount_intent' => 'Products',
			'products'        => array( 'all' ),
			'discount_method' => 'automated',
			'discount_type'   => 'percent',
			'discount_value'  => '10',
		);

		$this->campaign = ( new Campaign() )->save_campaign( $campaign );

		// Set up a REST server instance.
		global $wp_rest_server;

		$this->server = $wp_rest_server = new \WP_REST_Server();
		do_action( 'rest_api_init' );

		// Helper.
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME;

		$this->request = new \WP_REST_Request( 'GET', $this->route_name );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
	}

	public function tearDown(): void {
		// Your tear-down methods here.
		// Test cleanup.
		global $wp_rest_server;
		$wp_rest_server = null;

		// Destroy the user.
		$this->manager->destroy( $this->token );
		// Then...
		parent::tearDown();
	}

	// Tests

	public function testRouteIsRegistered() {
		$routes = $this->server->get_routes();

		$this->assertArrayHasKey( $this->route_name, $routes );
	}

	public function testPostRequestCreateACampaign() {
		// Campaign should be created by now before making this request.

		$body_params2     = array(
			'name'              => 'Test2 Product',
			'priority'          => '1',
			'status'            => '1',
			'discount_intent'   => 'Products',
			'products'          => array( 'all' ),
			'discount_method'   => 'automated',
			'discount_based_on' => 'cart_subtotal',
		);
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME;
		$this->request    = new \WP_REST_Request( 'POST', $this->route_name );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$this->request->set_body_params( $body_params2 );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		// $this->campaign = new Config($data);

		codecept_debug( $response->get_status() );

		$this->assertEquals( 'Test2 Product', $data['name'] );
	}

	public function testGetRequestWasSuccessful() {
		// Campaign should be created by now before making this request.

		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( 200, $response->status );
	}

	public function testGetRequestReturnsCampaigns() {
		// Campaign should be created by now before making this request.

		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();
		// codecept_debug($data);
		$this->assertEquals( $data[0]['name'], $this->campaign->name, json_encode( $data ) );
	}

	public function testGetRequestReturnsACampaign() {
		// Campaign should be created by now before making this request.
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME . '/' . $this->campaign->id;
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( $data[0]['name'], $this->campaign->name );
	}



	public function testPutRequestToUpdateACampaign() {
		// Campaign should be created by now before making this request.

		$body_params      = array(
			'name'              => 'Test2 Product Intent Campaign with automated 10% discount',
			'priority'          => '1',
			'status'            => '1',
			'discount_intent'   => 'Products',
			'products'          => array( 'all' ),
			'discount_method'   => 'automated',
			'discount_based_on' => 'cart_subtotal',
		);
		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME . '/' . $this->campaign->id;
		$this->request    = new \WP_REST_Request( 'PUT', $this->route_name );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_body_params( $body_params );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();
		// codecept_debug( $data );
		$this->assertEquals( $body_params['name'], $data['name'] );
	}

	public function testPatchRequestToUpdateACampaign() {
		// Campaign should be created by now before making this request.
		$body_params = array(
			'discount_method' => 'automated',
		);

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME . '/' . $this->campaign->id;
		$this->request    = new \WP_REST_Request( 'PUT', $this->route_name );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );
		$this->request->set_body_params( $body_params );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertEquals( $data['discount_method'], $body_params['discount_method'] );
	}

	public function testDeleteRequestToDeleteACampaign() {
		// Campaign should be created by now before making this request.

		$this->route_name = '/' . Api::NAMESPACE_NAME . '/' . Api::VERSION . '/' . Api::CAMPAIGN_ROUTE_NAME . '/' . $this->campaign->id;
		$this->request    = new \WP_REST_Request( 'DELETE', $this->route_name );
		$this->request->add_header( 'Content-Type', 'application/json;charset=utf-8' );

		$response = $this->server->dispatch( $this->request );
		$data     = $response->get_data();

		$this->assertTrue( $data['deleted'] );
		$this->assertEquals( $data['previous']['name'], $this->campaign->name );
	}
}
