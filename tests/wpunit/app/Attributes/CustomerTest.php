<?php

namespace Disco\Tests\WPUnit\App\Attributes;

use Disco\App\Attributes\Customer;

class CustomerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	protected $customer;

	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->customer = $this->tester->create_customer();

		wp_set_current_user( $this->customer->ID );
		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function test_customer_name() {
		$customerInfo = new Customer();

		$this->assertEquals( 'John Doe', $customerInfo->customer_name() );
	}

	public function test_customer_email() {
		$customerInfo = new Customer();

		$this->assertEquals( 'wahid0003@gmail.com', $customerInfo->customer_email() );
	}

	public function test_customer_is_logged_in() {
		$customerInfo = new Customer();

		$this->assertEquals( 'Yes', $customerInfo->customer_is_logged_in() );
	}

	public function test_customer_role() {
		$customerInfo = new Customer();

		$this->assertEquals( 'customer', $customerInfo->customer_user_role() );
	}

	public function test_customer_billing_country() {
		$customerInfo = new Customer();

		$this->assertEquals( 'BD', $customerInfo->customer_country() );
	}

	public function test_customer_billing_state() {
		$customerInfo = new Customer();

		$this->assertEquals( 'BD-13', $customerInfo->customer_state() );
	}

	public function test_customer_billing_city() {
		$customerInfo = new Customer();

		$this->assertEquals( 'Dhaka', $customerInfo->customer_city() );
	}

	public function test_customer_billing_postcode() {
		$customerInfo = new Customer();

		$this->assertEquals( '1207', $customerInfo->customer_zip() );
	}
}
