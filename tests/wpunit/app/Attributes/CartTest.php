<?php

namespace Disco\Tests\WPUnit\App\Attributes;

use Disco\App\Attributes\Cart;
use Disco\App\Utility\Helper;

class CartTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	protected $product;

	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->product = $this->tester->create_simple_product( 1 );

		$cart = WC()->cart;
		$cart->add_to_cart( $this->product->get_id() );

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.
		$cart = WC()->cart;
		$cart->empty_cart();
		// Then...
		parent::tearDown();
	}

	// Tests

	public function test_line_items_count() {
		$product  = $this->product;
		$cartInfo = new Cart();
		$cart     = WC()->cart;

		$this->assertEquals( $cart->get_cart_contents_count(), $cartInfo->line_items_count() );
	}

	public function test_cart_items_quantity() {
		$product  = $this->product;
		$cart     = WC()->cart;
		$cartInfo = new Cart();

		$this->assertEquals( array_sum( $cart->get_cart_item_quantities() ), $cartInfo->cart_items_quantity() );
	}

	public function test_cart_total_weight() {
		$product  = $this->product;
		$cart     = WC()->cart;
		$cartInfo = new Cart();

		$this->assertEquals( $cart->get_cart_contents_weight(), $cartInfo->cart_total_weight() );
	}

	public function test_cart_subtotal() {
		$product  = $this->product;
		$cart     = WC()->cart;
		$cartInfo = new Cart();

		$this->assertEquals( $cart->get_subtotal(), $cartInfo->cart_subtotal() );
	}

	public function test_cart_payment_method() {
		$product  = $this->product;
		$cart     = WC()->cart;
		$cartInfo = new Cart();

		$this->assertEquals( WC()->session->get( 'chosen_payment_method' ), $cartInfo->cart_payment_method() );
	}

	public function test_cart_coupons() {
		$product  = $this->product;
		$cart     = WC()->cart;
		$cartInfo = new Cart();

		$this->assertEquals( $cart->get_coupons(), $cartInfo->cart_coupons() );
	}
}
