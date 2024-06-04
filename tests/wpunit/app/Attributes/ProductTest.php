<?php

namespace Disco\Tests\WPUnit\App\Attributes;

use Codeception\TestCase\WPTestCase;
use Disco\App\Attributes\AttributeFactory;
use Disco\App\Utility\Config;

class ProductTest extends WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	protected $product;
	protected $config;

	/**
	 * @throws \WC_Data_Exception
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();

		$this->product = $this->tester->create_simple_product( 1 );
		$this->config  = new Config( array() );

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests


	public function test_product_attribute_id() {
		$product = $this->product;

		$this->assertEquals( $product->get_id(), AttributeFactory::get_value( 'id', array( 'product' => $product ) ) );
	}

	/**
	 * @throws \WC_Data_Exception
	 */
	public function test_product_attribute_sku() {
		$product = $this->product;

		$product->set_sku( 'test_sku_abc' );
		$product->save();

		$this->assertEquals( 'test_sku_abc', AttributeFactory::get_value( 'sku', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_title() {
		$product = $this->product;

		$this->assertEquals( $product->get_name(), AttributeFactory::get_value( 'title', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_description() {
		$product = $this->product;

		$this->assertEquals( $product->get_description(), AttributeFactory::get_value( 'description', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_short_description() {
		$product = $this->product;

		$this->assertEquals( $product->get_short_description(), AttributeFactory::get_value( 'short_description', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_attributes() {
		$product = $this->product;

		$this->assertEquals( array_keys( $product->get_attributes() ), AttributeFactory::get_value( 'attributes', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_categories() {
		$product = $this->product;

		$this->assertEquals( $product->get_category_ids(), AttributeFactory::get_value( 'categories', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_tags() {
		$product = $this->product;

		$this->assertEquals( $product->get_tag_ids(), AttributeFactory::get_value( 'tags', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_link() {
		$product = $this->product;

		$this->assertEquals( $product->get_permalink(), AttributeFactory::get_value( 'link', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_availability() {
		$product = $this->product;

		$this->assertEquals( $product->get_availability(), AttributeFactory::get_value( 'availability', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_quantity() {
		$product = $this->product;

		$this->assertEquals( $product->get_stock_quantity(), AttributeFactory::get_value( 'quantity', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_stock_status() {
		$product = $this->product;

		$this->assertEquals( $product->get_stock_status(), AttributeFactory::get_value( 'stock_status', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_weight() {
		$product = $this->product;

		$this->assertEquals( $product->get_weight(), AttributeFactory::get_value( 'weight', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_weight_unit() {
		$product = $this->product;

		$this->assertEquals( get_option( 'woocommerce_weight_unit' ), AttributeFactory::get_value( 'weight_unit', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_width() {
		$product = $this->product;

		$this->assertEquals( $product->get_width(), AttributeFactory::get_value( 'width', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_height() {
		$product = $this->product;

		$this->assertEquals( $product->get_height(), AttributeFactory::get_value( 'height', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_length() {
		$product = $this->product;

		$this->assertEquals( $product->get_length(), AttributeFactory::get_value( 'length', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_type() {
		$product = $this->product;

		$this->assertEquals( $product->get_type(), AttributeFactory::get_value( 'type', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_visibility() {
		$product = $this->product;

		$this->assertEquals( $product->get_catalog_visibility(), AttributeFactory::get_value( 'visibility', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_rating_total() {
		$product = $this->product;

		$this->assertEquals( $product->get_rating_count(), AttributeFactory::get_value( 'rating_total', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_rating_average() {
		$product = $this->product;

		$this->assertEquals( $product->get_average_rating(), AttributeFactory::get_value( 'rating_average', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_author_name() {
		$product = $this->product;

		$post   = get_post( $this->product->get_id() );
		$author = get_the_author_meta( 'user_login', $post->post_author );
		$this->assertEquals( $author, AttributeFactory::get_value( 'author_name', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_author_email() {
		$product = $this->product;

		$post   = get_post( $this->product->get_id() );
		$author = get_the_author_meta( 'user_email', $post->post_author );
		$this->assertEquals( $author, AttributeFactory::get_value( 'author_email', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_date_created() {
		$product = $this->product;

		$this->assertEquals( ( $product->get_date_created() )->date_i18n(), AttributeFactory::get_value( 'date_created', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_date_updated() {
		$product = $this->product;

		$this->assertEquals( ( $product->get_date_modified() )->date_i18n(), AttributeFactory::get_value( 'date_updated', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_product_status() {
		$product = $this->product;

		$this->assertEquals( $product->get_status(), AttributeFactory::get_value( 'product_status', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_featured_status() {
		$product = $this->product;

		$this->assertEquals( $product->is_featured(), AttributeFactory::get_value( 'featured_status', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_currency() {
		$product = $this->product;

		$this->assertEquals( get_woocommerce_currency(), AttributeFactory::get_value( 'currency', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_regular_price() {
		$product = $this->product;

		$this->assertEquals( $product->get_regular_price(), AttributeFactory::get_value( 'regular_price', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_price() {
		$product = $this->product;

		$this->assertEquals( $product->get_price(), AttributeFactory::get_value( 'price', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_sale_price() {
		$product = $this->product;

		$this->assertEquals( $product->get_sale_price(), AttributeFactory::get_value( 'sale_price', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_regular_price_with_tax() {
		$product = $this->product;

		$this->assertEquals( $product->get_regular_price(), AttributeFactory::get_value( 'regular_price_with_tax', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_price_with_tax() {
		$product = $this->product;

		$this->assertEquals( $product->get_price(), AttributeFactory::get_value( 'price_with_tax', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_sale_price_with_tax() {
		$product = $this->product;

		$this->assertEquals( $product->get_sale_price(), AttributeFactory::get_value( 'sale_price_with_tax', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_sale_price_sdate() {
		$product = $this->product;

		$this->assertEquals( $product->get_date_on_sale_from(), AttributeFactory::get_value( 'sale_price_sdate', array( 'product' => $product ) ) );
	}

	public function test_product_attribute_sale_price_edate() {
		$product = $this->product;

		$this->assertEquals( $product->get_date_on_sale_to(), AttributeFactory::get_value( 'sale_price_edate', array( 'product' => $product ) ) );
	}

	// Test Product Global Attributes

	public function test_product_global_attribute() {
		$product    = $this->product;
		$attributes = $this->tester->create_attributes();
		$this->assertEquals( '', AttributeFactory::get_value( 'global_attribute_pa_colors', array( 'product' => $product ), 'Its ok to be failed is the product does not have any attribute' ) );
	}

	// Test Product Custom Attributes

	public function test_product_meta() {
		$product = $this->product;

		$product->set_total_sales( 100 );
		$product->save();

		$this->assertEquals( $product->get_total_sales(), AttributeFactory::get_value( 'product_meta__total_sales', array( 'product' => $product ) ) );
	}

	public function test_tax_class() {
		$taxClass = $this->product->get_tax_class();
		if ( empty( $taxClass ) ) {
			$taxClass = 'standard';
		}
		$this->assertEquals( $taxClass, AttributeFactory::get_value( 'tax_class', array( 'product' => $this->product ) ) );
	}

	// q: how to remove git cache?
	// a: git rm -r --cached .
	public function test_tax_status() {
		$taxStatus = $this->product->get_tax_status();

		$this->assertEquals( $taxStatus, AttributeFactory::get_value( 'tax_status', array( 'product' => $this->product ) ) );
	}

	public function test_shipping_class() {
		$this->assertEquals( $this->product->get_shipping_class(), AttributeFactory::get_value( 'shipping_class', array( 'product' => $this->product ) ) );
	}
}
