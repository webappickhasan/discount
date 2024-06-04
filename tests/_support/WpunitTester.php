<?php

use Disco\Backend\ActDeact as ActDeactAlias;

/**
 * Inherited Methods
 *
 * @method void wantToTest( $text )
 * @method void wantTo( $text )
 * @method void execute( $callable )
 * @method void expectTo( $prediction )
 * @method void expect( $prediction )
 * @method void amGoingTo( $argumentation )
 * @method void am( $role )
 * @method void lookForwardTo( $achieveValue )
 * @method void comment( $description )
 * @method \Codeception\Lib\Friend haveFriend( $name, $actorClass = null )
 *
 * @SuppressWarnings(PHPMD)
 */
class WpunitTester extends \Codeception\Actor {
	use _generated\WpunitTesterActions;

	/**
	 * Define custom actions here
	 */
	public function __construct() {
		$this->create_db_table();
	}

	public function create_campaign( $arg = array() ) {
		$data = array(
			'name'            => 'Test Campaign',
			'status'          => '1',
			'discount_intent' => 'Product',
			'products'        => array( 'all' ),
			'discount_rules'  => array(
				array(
					'min'            => 5,
					'discount_type'  => 'percent',
					'discount_value' => 10,
					'get_quantity'   => '',
					'get_ids'        => array(),
					'discount_label' => '10% off',
				),
			),
		);

		$data     = array_merge( $data, $arg );
		$campaign = new \Disco\App\Campaign();

		return $campaign->save_campaign( $data );
	}

	public function add_product_to_cart( $products, $quantities = array() ) {
		$cart = WC()->cart;
		foreach ( $products as $product ) {
			$product_id = $product->get_id();
			$quantity   = isset( $quantities[ $product_id ] ) ? $quantities[ $product_id ] : 1;
			$cart->add_to_cart( $product_id, $quantity );
		}

		return $cart;
	}

	public function create_db_table() {
		return ActDeactAlias::create_plugin_table();
	}

	public function create_customer() {

		$first_name = 'John';
		$last_name  = 'Doe';
		$email      = 'wahid0003@gmail.com';
		$customer   = new WC_Customer();
		$customer->set_username( 'JohnDoe' );
		$customer->set_first_name( $first_name );
		$customer->set_last_name( $last_name );
		$customer->set_email( $email );
		$customer->set_billing_first_name( $first_name );
		$customer->set_billing_last_name( $last_name );
		$customer->set_billing_email( $email );
		$customer->set_billing_phone( '123456789' );
		$customer->set_billing_country( 'BD' );
		$customer->set_billing_state( 'BD-13' );
		$customer->set_billing_city( 'Dhaka' );
		$customer->set_billing_postcode( '1207' );

		$customer->save();

		return $customer;
	}

	/**
	 * @throws \WC_Data_Exception
	 */
	public function create_wc_order( $data ) {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		$order    = new WC_Order();

		// Set Billing and Shipping adresses
		foreach ( array( 'billing_', 'shipping_' ) as $type ) {
			foreach ( $data['address'] as $key => $value ) {
				if ( $type === 'shipping_' && in_array( $key, array( 'email', 'phone' ) ) ) {
					continue;
				}

				$type_key = $type . $key;

				if ( is_callable( array( $order, "set_{$type_key}" ) ) ) {
					$order->{"set_{$type_key}"}( $value );
				}
			}
		}

		// Set other details
		$order->set_created_via( 'programatically' );
		$order->set_customer_id( $data['user_id'] );
		$order->set_currency( get_woocommerce_currency() );
		$order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
		$order->set_customer_note( isset( $data['order_comments'] ) ? $data['order_comments'] : '' );
		$order->set_payment_method( isset( $gateways[ $data['payment_method'] ] ) ? $gateways[ $data['payment_method'] ] : $data['payment_method'] );

		$calculate_taxes_for = array(
			'country'  => $data['address']['country'],
			'state'    => $data['address']['state'],
			'postcode' => $data['address']['postcode'],
			'city'     => $data['address']['city'],
		);

		// Line items
		foreach ( $data['line_items'] as $line_item ) {
			$args    = $line_item['args'];
			$product = wc_get_product( isset( $args['variation_id'] ) && $args['variation_id'] > 0 ? $$args['variation_id'] : $args['product_id'] );
			$item_id = $order->add_product( $product, $line_item['quantity'], $line_item['args'] );

			$item = $order->get_item( $item_id, false );

			$item->calculate_taxes( $calculate_taxes_for );
			$item->save();
		}

		// Coupon items
		if ( isset( $data['coupon_items'] ) ) {
			foreach ( $data['coupon_items'] as $coupon_item ) {
				$order->apply_coupon( sanitize_title( $coupon_item['code'] ) );
			}
		}

		// Fee items
		if ( isset( $data['fee_items'] ) ) {
			foreach ( $data['fee_items'] as $fee_item ) {
				$item = new WC_Order_Item_Fee();

				$item->set_name( $fee_item['name'] );
				$item->set_total( $fee_item['total'] );
				$tax_class = isset( $fee_item['tax_class'] ) && $fee_item['tax_class'] != 0 ? $fee_item['tax_class'] : 0;
				$item->set_tax_class( $tax_class ); // O if not taxable

				$item->calculate_taxes( $calculate_taxes_for );

				$item->save();
				$order->add_item( $item );
			}
		}

		// Set calculated totals
		$order->calculate_totals();
		$order->save();
		if ( isset( $data['order_status'] ) ) {
			// Update order status from pending to your defined status and save data
			$order->update_status( $data['order_status']['status'], $data['order_status']['note'] );
			$order_id = $order->get_id();
		} else {
			// Save order to database (returns the order ID)
			$order_id = $order->save();
		}

		// Returns the order ID
		return $order_id;
	}

	/**
	 * @throws \WC_Data_Exception
	 */
	public function create_simple_product( $id = null ) {

		$attributes = $this->create_attributes();
		$categories = $this->create_categories();
		$tags       = $this->create_tags();

		// that's CRUD object
		$product = new WC_Product_Simple();

		$name = 'Simple Product ' . $id;

		$product->set_name( $name ); // product title
		$product->set_slug( str_replace( ' ', '-', strtolower( $name ) ) ); // product title
		$product->set_short_description( $name . ' short description' );
		// you can also add a full product description
		$product->set_description( $name . ' description' );
		$product->set_image_id( 90 );
		// let's suppose that our 'Accessories' category has ID = 19
		$product->set_category_ids( $categories );
		// you can also use $product->set_tag_ids() for tags, brands etc
		$product->set_tag_ids( $tags );

		// Set product attributes.
		$raw_attributes = array();
		foreach ( $attributes as $aid => $attribute ) {
			$attrObject = new WC_Product_Attribute();
			$attrObject->set_id( $attribute['id'] );
			$attrObject->set_name( $attribute['name'] );
			$attrObject->set_options( $attribute['options'] );
			$attrObject->set_position( 0 );
			$attrObject->set_visible( true );
			$attrObject->set_variation( true ); // here it is

			$raw_attributes[ $aid ] = $attrObject;
		}

		$product->set_attributes( $raw_attributes );

		$product->set_stock_quantity( 100 );
		$product->set_stock_status( 'instock' );
		$product->set_manage_stock( true );
		$product->set_reviews_allowed( true );
		$product->set_sold_individually( false );
		$product->set_status( 'publish' );
		$product->set_backorders( 'no' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_sku( 'simple_sku_' . $id );
		$product->set_regular_price( 100 );
		$product->set_sale_price( 80 );
		$product->set_price( 80 );
		$product->set_tax_status( 'taxable' );
		$product->set_purchase_note( '' );
		$product->set_featured( true );

		// Set WooCommerce Weight Unit.
		update_option( 'woocommerce_weight_unit', 'kg' );
		update_option( 'woocommerce_dimension_unit', 'cm' );
		$product->set_weight( 1.5 );
		$product->set_length( 2 );
		$product->set_width( 2 );
		$product->set_height( 2 );

		// Review
		$product->set_rating_counts( array( 5, 4, 4.5 ) );
		$product->set_average_rating( 4.91 );

		$product->set_date_created( date( 'Y-m-d H:i:s' ) );
		$product->set_date_modified( date( 'Y-m-d H:i:s' ) );

		// Save product
		$product->save();

		return $product;
	}

	public function create_variable_product( $id = null ) {

		$attributes = $this->create_attributes();
		$categories = $this->create_categories();
		$tags       = $this->create_tags();

		// Creating a variable product
		$product = new WC_Product_Variable();

		$variable_name = 'Variable Product ' . $id;
		// Name and image would be enough
		$product->set_name( $variable_name );
		$product->set_image_id( 90 );
		$product->set_category_ids( $categories );
		$product->set_tag_ids( $tags );
		$product->set_attributes( $attributes );

		// one available for variation attribute
		$attribute1 = new WC_Product_Attribute();
		$attribute1->set_name( 'Color 1' );
		$attribute1->set_options( array( 'Red', 'Green' ) );
		$attribute1->set_position( 0 );
		$attribute1->set_visible( true );
		$attribute1->set_variation( true ); // here it is

		$attribute2 = new WC_Product_Attribute();
		$attribute2->set_name( 'Size 1' );
		$attribute2->set_options( array( 'Small', 'Medium', 'Large' ) );
		$attribute2->set_position( 0 );
		$attribute2->set_visible( true );
		$attribute2->set_variation( true ); // here it is

		$product->set_attributes( array( $attribute1, $attribute2 ) );

		// save the changes and go on
		$product->save();

		// Variation 1
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product->get_id() );
		$variation->set_attributes(
			array(
				'pa_colors' => 'Red',
				'pa_sizes'  => 'Small',
			)
		);
		$variation->set_regular_price( 100 ); // yep, magic hat is quite expensive
		$variation->set_sale_price( 80 ); // yep, magic hat is quite expensive
		$variation->save();

		// Variation 1
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product->get_id() );
		$variation->set_attributes(
			array(
				'pa_colors' => 'Green',
				'pa_sizes'  => 'Medium',
			)
		);
		$variation->set_regular_price( 80 ); // yep, magic hat is quite expensive
		$variation->set_sale_price( 60 ); // yep, magic hat is quite expensive
		$variation->save();
	}

	public function create_tags() {
		$tags = array( 'Tag 1', 'Tag 2', 'Tag 3' );

		// Create tags if tag doesn't exist.
		return array_map(
			static function ( $tag ) {
				$id = term_exists( $tag, 'product_tag' );
				if ( ! is_array( $id ) ) {
					$id = wp_insert_term( $tag, 'product_tag' );
				}

				if ( is_array( $id ) && isset( $id['term_id'] ) ) {
					return $id['term_id'];
				}

				return $id;
			},
			$tags
		);
	}

	public function create_categories() {
		$categories = array( 'Category 1', 'Category 2', 'Category 3' );

		return array_map(
			static function ( $category ) {
				$id = term_exists( $category, 'product_cat' );
				if ( ! is_array( $id ) ) {
					$id = wp_insert_term( $category, 'product_cat' );
				}

				if ( is_array( $id ) && isset( $id['term_id'] ) ) {
					return $id['term_id'];
				}

				return $id;
			},
			$categories
		);
	}

	// Create Product Attributes.
	public function create_attributes() {
		$attributes = array(
			'Color'   => array( 'Red', 'Green', 'Blue' ),
			'Sizes'   => array( 'Small', 'Medium', 'Large' ),
			'Weights' => array( 'Light', 'Medium', 'Heavy' ),
		);

		$attribute_ids = array();

		foreach ( $attributes as $attribute => $terms ) {

			$attr = array(
				'name'     => $attribute,
				'type'     => 'select',
				'order_by' => 'menu_order',
			);

			// Create the attribute
			$id = wc_create_attribute( $attr );
			// Taxonomy attribute slug.
			$taxonomy = wc_attribute_taxonomy_name( $attribute );

			$attribute_ids[ $id ]['id']   = $id;
			$attribute_ids[ $id ]['name'] = $attribute;
			// $attribute_ids[ $id ]['slug']    = $taxonomy;
			$attribute_ids[ $id ]['options'] = $terms;

			if ( taxonomy_exists( $taxonomy ) ) {
				foreach ( $terms as $term ) {
					// Check if the Term name already exist in database to avoid duplicate entries
					if ( ! term_exists( $term, $taxonomy ) ) {
						wp_insert_term( $term, $taxonomy );
					}
				}
			}
		}

		return $attribute_ids;
	}

	public function create_coupon( $code ) {
		$coupon = new WC_Coupon();

		$coupon->set_code( $code );
		$coupon->set_amount( 10 );
		$coupon->set_discount_type( 'fixed_cart' );
		// $coupon->set_individual_use( true );
		// $coupon->set_usage_limit( 1 );
		$coupon->save();

		return $coupon;
	}
}
