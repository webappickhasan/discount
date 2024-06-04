<?php

namespace Disco\Tests\WPUnit\App\Utility;

use Disco\App\Utility\Search;

class SearchTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests

	/**
	 * @throws \WC_Data_Exception
	 */
	public function testSearchProductsWithValidTerm() {
		// Test with valid term

		// Create 2 simple products
		$product1 = $this->tester->create_simple_product( 1 );
		$product2 = $this->tester->create_simple_product( 2 );

		// Test 1: Search products with the title of the first product
		$term_title = 'product 1';
		$products   = Search::products( $term_title );

		// Assert that the products array is not empty
		$this->assertNotEmpty( $products );
		// Assert that the products array has 1 product
		$this->assertCount( 1, $products );

		// Test 2:  Search products with the sku of the second product
		$term_sku = $product2->get_sku();
		$products = Search::products( $term_sku );

		// Assert that the products array is not empty
		$this->assertNotEmpty( $products );
		// Assert that the products array has 1 product
		$this->assertCount( 1, $products );

		// Test 3: Search products with the id of the first product
		$term_id  = $product1->get_id();
		$products = Search::products( $term_id );

		// Assert that the products array is not empty
		$this->assertNotEmpty( $products );
		// Assert that the products array has 1 product
		$this->assertCount( 1, $products );
	}

	public function testSearchProductsWithEmptyTerm() {
		// Test with empty term
		$term = 'not a product';
		$this->assertEquals( array(), Search::products( $term ) );
	}

	public function testSearchProductsWithException() {
		// Test with exception
		$term = new \WP_Error();
		$this->assertEquals( array(), Search::products( $term ) );
	}

	public function testSearchProductsWithInvalidProductId() {
		// Test with invalid product id
		$term = 999999;
		$this->assertEquals( array(), Search::products( $term ) );
	}

	public function testSearchProductsWithInvalidProductSku() {
		// Test with invalid product sku
		$term = 'invalid-sku';
		$this->assertEquals( array(), Search::products( $term ) );
	}

	public function testSearchCategoriesWithValidTerm() {
		// Create product categories
		$category1 = wp_insert_term( 'Category 1', 'product_cat' );
		$category2 = wp_insert_term( 'Category 2', 'product_cat' );
		$category3 = wp_insert_term( 'Category 3', 'product_cat' );

		// Test 1: Search categories with the name of the first category
		$term       = 'Category 1';
		$categories = Search::categories( $term );

		// Assert that the categories array is not empty
		$this->assertNotEmpty( $categories );
		// Assert that the categories array has 1 category
		$this->assertCount( 1, $categories );
		// Assert that the returned category is the one we searched for
		$this->assertEquals( $category1['term_id'], $categories[0]['id'] );
		$this->assertEquals( 'Category 1', $categories[0]['name'] );

		// Test 2: Search categories with a term that matches multiple categories
		$term       = 'Category';
		$categories = Search::categories( $term );

		// Assert that the categories array is not empty
		$this->assertNotEmpty( $categories );
		// Assert that the categories array has 3 categories
		$this->assertCount( 3, $categories );
	}

	public function testSearchCategoriesWithEmptyTermNoCategories() {
		// Test with empty term
		$term = '';
		// Assert that the categories array has 1 category (Uncategorized)
		$this->assertCount( 1, Search::categories( $term ) );
	}

	public function testSearchCategoriesWithEmptyTermAllCategories() {
		$category1 = wp_insert_term( 'Category 1', 'product_cat' );
		$category2 = wp_insert_term( 'Category 2', 'product_cat' );
		$category3 = wp_insert_term( 'Category 3', 'product_cat' );
		// Test with empty term
		$term = '';
		// Assert that the categories array has all categories + (Uncategorized)
		$this->assertCount( 4, Search::categories( $term ) );
	}

	public function testSearchCategoriesWithInvalidTerm() {
		// Test with invalid term
		$term = 'not a category';
		$this->assertEquals( array(), Search::categories( $term ) );
	}

	public function testSearchTagsWithValidTerm() {
		// Create product tags
		$tag1 = wp_insert_term( 'Tag 1', 'product_tag' );
		$tag2 = wp_insert_term( 'Tag 2', 'product_tag' );
		$tag3 = wp_insert_term( 'Tag 3', 'product_tag' );

		// Test 1: Search tags with the name of the first tag
		$term = 'Tag 1';
		$tags = Search::tags( $term );

		// Assert that the tags array is not empty
		$this->assertNotEmpty( $tags );
		// Assert that the tags array has 1 tag
		$this->assertCount( 1, $tags );
		// Assert that the returned tag is the one we searched for
		$this->assertEquals( $tag1['term_id'], $tags[0]['id'] );
		$this->assertEquals( 'Tag 1', $tags[0]['name'] );

		// Test 2: Search tags with a term that matches multiple tags
		$term = 'Tag';
		$tags = Search::tags( $term );

		// Assert that the tags array is not empty
		$this->assertNotEmpty( $tags );
		// Assert that the tags array has 3 tags
		$this->assertCount( 3, $tags );
	}

	public function testSearchTagsWithEmptyTerm() {
		// Test with empty term
		$term = '';
		$this->assertEquals( array(), Search::tags( $term ) );
	}

	public function testSearchTagsWithInvalidTerm() {
		// Test with invalid term
		$term = 'not a tag';
		$this->assertEquals( array(), Search::tags( $term ) );
	}

	public function testSearchCouponsWithValidTerm() {
		// Create a coupon
		$coupon = $this->tester->create_coupon( 'testcoupon' );

		// Test: Search coupons with the code of the coupon
		$term    = 'testcoupon';
		$coupons = Search::coupons( $term );

		// Assert that the coupons array is not empty
		$this->assertNotEmpty( $coupons );
		// Assert that the coupons array has 1 coupon
		$this->assertCount( 1, $coupons );
		// Assert that the returned coupon is the one we searched for
		$this->assertEquals( $coupon->get_id(), $coupons[0]['id'] );
		$this->assertEquals( 'testcoupon', $coupons[0]['name'] );
	}

	public function TestSearchCouponsWithEmptyTerm() {
		// Search coupons with empty term
		$term    = '';
		$coupons = Search::coupons( $term );

		// Assert that the coupons array is empty
		$this->assertEmpty( $coupons );
	}

	public function TestSearchCouponsWithInvalidTerm() {
		// Search coupons with invalid term
		$term    = 'invalidcoupon';
		$coupons = Search::coupons( $term );

		// Assert that the coupons array is empty
		$this->assertEmpty( $coupons );
	}

	public function testSearchCustomersWithValidTerm() {
		// Create a customer
		$customer = $this->tester->create_customer();

		// Test: Search customers with the name of the customer
		$term      = 'John';
		$customers = Search::customers( $term );

		// Assert that the customers array is not empty
		$this->assertNotEmpty( $customers );
		// Assert that the customers array has 1 customer
		$this->assertCount( 1, $customers );
		// Assert that the returned customer is the one we searched for
		$this->assertEquals( $customer->get_id(), $customers[0]['id'] );
		$this->assertEquals( 'John Doe (wahid0003@gmail.com)', $customers[0]['name'] );
	}

	public function TestSearchCustomersWithValidName() {
		// Create a customer
		$customer = $this->tester->create_customer();

		// Search customers with the name of the customer
		$term      = 'John Doe';
		$customers = Search::customers( $term );

		// Assert that the customers array is not empty
		$this->assertNotEmpty( $customers );
		// Assert that the customers array has 1 customer
		$this->assertCount( 1, $customers );
		// Assert that the returned customer is the one we searched for
		$this->assertEquals( $customer->get_id(), $customers[0]['id'] );
		$this->assertEquals( 'John Doe (wahid0003@gmail.com)', $customers[0]['name'] );
	}

	public function TestSearchCustomersWithValidEmail() {
		// Create a customer
		$customer = $this->tester->create_customer();

		// Search customers with the email of the customer
		$term      = 'wahid0003@gmail.com';
		$customers = Search::customers( $term );

		// Assert that the customers array is not empty
		$this->assertNotEmpty( $customers );
		// Assert that the customers array has 1 customer
		$this->assertCount( 1, $customers );
		// Assert that the returned customer is the one we searched for
		$this->assertEquals( $customer->get_id(), $customers[0]['id'] );
		$this->assertEquals( 'John Doe (wahid0003@gmail.com)', $customers[0]['name'] );
	}

	public function TestSearchCustomersWithEmptyTerm() {
		// Search customers with empty term
		$term      = '';
		$customers = Search::customers( $term );

		// Assert that the customers array is empty
		$this->assertEmpty( $customers );
	}

	public function TestSearchCustomersWithInvalidTerm() {
		// Search customers with invalid term
		$term      = 'invalidcustomer';
		$customers = Search::customers( $term );

		// Assert that the customers array is empty
		$this->assertEmpty( $customers );
	}

	public function testSearchStatesWithValidTerm() {
		// Test: Get states by country code
		$country = 'US';
		$states  = Search::states( $country );

		// Assert that the states array is not empty
		$this->assertNotEmpty( $states );
	}

	public function TestStatesReturnsCorrectDataForValidCountryCode() {
		// Given a valid country code
		$country = 'US';

		// When we call the states method
		$states = Search::states( $country );

		// Then we should get an array of states
		$this->assertIsArray( $states );
		// And each state should have an 'id' and 'name'
		foreach ( $states as $state ) {
			$this->assertArrayHasKey( 'id', $state );
			$this->assertArrayHasKey( 'name', $state );
		}
	}

	public function TestStatesReturnsEmptyArrayForInvalidCountryCode() {
		// Given an invalid country code
		$country = 'INVALID';

		// When we call the states method
		$states = Search::states( $country );

		// Then we should get an empty array
		$this->assertEmpty( $states );
	}

	public function TestStatesReturnsEmptyArrayForNullCountryCode() {
		// Given a null country code
		$country = null;

		// When we call the states method
		$states = Search::states( $country );

		// Then we should get an empty array
		$this->assertEmpty( $states );
	}

	public function testSearchCountriesWithValidTerm() {
		// Test: Search countries with a term
		$term      = 'United';
		$countries = Search::countries( $term );

		// Assert that the countries array is not empty
		$this->assertNotEmpty( $countries );
	}

	public function testSearchAttributesWithValidTerm() {
		// Create product attributes using WC_Product_Attribute class
		$attribute = $this->tester->create_attributes();
		// Test: Search attributes with a term
		$term       = 'Color';
		$attributes = Search::attributes( $term );

		// Assert that the attributes array is not empty
		$this->assertNotEmpty( $attributes );
	}

	public function TestAttributesReturnsCorrectDataForValidTerm() {
		// Given a valid term
		$term = 'Color';

		// When we call the attributes method
		$attributes = Search::attributes( $term );

		// Then we should get an array of attributes
		$this->assertIsArray( $attributes );
		// And each attribute should have an 'id' and 'name'
		foreach ( $attributes as $attribute ) {
			$this->assertArrayHasKey( 'id', $attribute );
			$this->assertArrayHasKey( 'name', $attribute );
		}
	}

	public function TestAttributesReturnsEmptyArrayForEmptyTerm() {
		// Given an empty term
		$term = '';

		// When we call the attributes method
		$attributes = Search::attributes( $term );

		// Then we should get an empty array
		$this->assertEmpty( $attributes );
	}

	public function TestAttributesReturnsEmptyArrayForInvalidTerm() {
		// Given an invalid term
		$term = 'invalidattribute';

		// When we call the attributes method
		$attributes = Search::attributes( $term );

		// Then we should get an empty array
		$this->assertEmpty( $attributes );
	}
}
