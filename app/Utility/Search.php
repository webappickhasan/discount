<?php //phpcs:ignore

/**
 * Search Utility
 *
 * @package    Disco
 * @subpackage App\Utility
 * @since      1.0.0
 * @category   Utility
 */

namespace Disco\App\Utility;

use WC_Countries;
use WC_Coupon;
use WC_Data_Store;
use WP_Query;
use WP_Term_Query;
use WP_User_Query;

/**
 * Class Search
 *
 * @package    Disco
 * @subpackage App\Utility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Utility
 */
class Search { //phpcs:ignore

	/**
	 * Search Products by Product Title or SKU.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function products( $like = '' ) {
		try {
			$data_store   = WC_Data_Store::load( 'product' );
			$get_products = $data_store->search_products( $like, 'product', true, false, 200, array(), array() ); // @phpstan-ignore-line
		} catch ( \Throwable $e ) {
			$get_products = array();
		}

		$products = array();

		if ( ! empty( $get_products ) ) {
			foreach ( $get_products as $pid ) {
				if ( ! $pid ) {
					continue;
				}

				$product = wc_get_product( $pid );

				if ( ! ( $product instanceof \WC_Product ) ) {
					continue;
				}

				$products[] = array(
					'id'    => $product->get_id(),
					'sku'   => $product->get_sku(),
					'name'  => $product->get_name(),
					'image' => wp_get_attachment_url( (int) $product->get_image_id() ),
				);
			}
		}

		return $products;
	}

	/**
	 * Search Categories by Category Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function categories( $like = '' ) {
		$args = array(
			'taxonomy'   => 'product_cat',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'number'     => 20,
		);

		if ( ! empty( $like ) ) {
			$args['search'] = $like;
		}

		$args           = (array) $args;
		$get_categories = ( new WP_Term_Query( $args ) )->get_terms();

		$categories = array();

		if ( is_array( $get_categories ) && ! empty( $get_categories ) ) {
			foreach ( $get_categories as $category ) {
				if ( ! ( $category instanceof \WP_Term ) ) {
					continue;
				}

				$categories[] = array(
					'id'   => $category->term_id,
					'name' => $category->name,
				);
			}
		}

		return $categories;
	}

	/**
	 * Search Tags by Tag Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function tags( $like = '' ) {
		$args = array(
			'taxonomy'   => 'product_tag',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'number'     => 20,
		);

		if ( ! empty( $like ) ) {
			$args['search'] = $like;
		}

		$get_tags = ( new WP_Term_Query( $args ) )->get_terms();

		$tags = array();

		if ( is_array( $get_tags ) && ! empty( $get_tags ) ) {
			foreach ( $get_tags as $tag ) {
				if ( ! ( $tag instanceof \WP_Term ) ) {
					continue;
				}

				$tags[] = array(
					'id'   => $tag->term_id,
					'name' => $tag->name,
				);
			}
		}

		return $tags;
	}

	/**
	 * Search coupon by coupon name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function coupons( $like = '' ) {
		$args = array(
			'post_type'   => 'shop_coupon',
			'post_status' => 'publish',
			'fields'      => 'post_title',
			'number'      => 20,

		);

		if ( ! empty( $like ) ) {
			$args['s'] = $like;
		}

		$get_coupons = ( new WP_Query( $args ) )->get_posts();

		$coupons = array();

		if ( ! empty( $get_coupons ) ) {
			foreach ( $get_coupons as $coupon ) {
				if ( ! ( $coupon instanceof \WP_Post ) ) {
					continue;
				}

				$coupon = new WC_Coupon( $coupon->post_title );

				if ( ! $coupon->is_valid_for_cart() ) {
					continue;
				}

				$coupons[] = array(
					'id'   => $coupon->get_id(),
					'name' => $coupon->get_code(),
				);
			}
		}

		return $coupons;
	}

	/**
	 * Search Customer by Name or Email.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function customers( $like = '' ) {
		$args = array(
			'role'    => 'customer',
			'order'   => 'ASC',
			'orderby' => 'display_name',
			'number'  => 20,
		);

		if ( ! empty( $like ) ) {
			$args['search'] = '*' . esc_attr( $like ) . '*';
		}

		if ( filter_var( $like, FILTER_VALIDATE_EMAIL ) ) {
			// valid email address
			$args['search_columns'] = array( 'user_email' );
		} else {
			// invalid address
			$args['meta_query'] = array( //phpcs:ignore
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $like,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'last_name',
					'value'   => $like,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'user_email',
					'value'   => $like,
					'compare' => 'LIKE',
				),
			);
		}

		// Create the WP_User_Query object
		$get_customers = ( new WP_User_Query( $args ) )->get_results();

		$customers = array();

		if ( ! empty( $get_customers ) ) {
			foreach ( $get_customers as $customer ) {
				$customers[] = array(
					'id'   => $customer->ID,
					'name' => $customer->first_name . ' ' . $customer->last_name . ' (' . $customer->user_email . ')',
				);
			}
		}

		return $customers;
	}

	/**
	 * Get States by Country Code.
	 *
	 * @param string $country Country Code.
	 * @return array
	 */
	public static function states( $country ) {
		$states     = array();
		$get_states = ( new WC_Countries )->get_states( $country );

		if ( $get_states ) {
			foreach ( $get_states as $key => $state ) {
				$states[] = array(
					'id'   => $key,
					'name' => $state,
				);
			}
		}

		return $states;
	}

	/**
	 * Get States by Country Code.
	 *
	 * @param string $like Country or state name.
	 * @return array
	 */
	public static function countries( $like ) {
		if ( empty( $like ) ) {
			return array();
		}

		$counties     = array();
		$get_counties = ( new WC_Countries )->get_countries();

		foreach ( $get_counties as $country_key => $country ) {
			$counties[ $country_key ] = array(
				'id'   => $country_key,
				'name' => $country,
			);
			$states                   = ( new WC_Countries )->get_states( $country_key );

			if ( empty( $states ) ) {
				continue;
			}

			foreach ( $states as $state_key => $state ) {
				$counties[ $country_key . ':' . $state_key ] = array(
					'id'   => $country_key . ':' . $state_key,
					'name' => $country . ' - ' . $state,
				);
			}
		}

		return array_filter(
			$counties,
			function ( $item ) use ( $like ) {
				$value = $item['id'] . '-' . $item['name'];

				return stripos( $value, $like ) !== false;
			}
		);
	}

	/**
	 * Search Attributes by Attribute Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function attributes( $like = ' ' ) {
		$attributes        = self::get_global_attributes( $like );
		$custom_attributes = self::get_custom_attributes( $like );

		return array_merge( $attributes, $custom_attributes );
	}

	/**
	 * Search Attributes by Attribute Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	protected static function get_global_attributes( $like ) {
		$global_attributes = Cache::remember(
			'wc_global_attributes',
			array(
				new Model,
				'wc_global_attribute_query',
			),
			array( $like )
		);
		$result            = array();

		if ( is_array( $global_attributes ) && ! empty( $global_attributes ) ) {
			foreach ( $global_attributes as $attribute ) {
				if ( ! isset( $attribute->attribute_name, $attribute->attribute_label ) ) {
					continue;
				}

				$result[] = array(
					'id'   => $attribute->attribute_name,
					'name' => $attribute->attribute_label,
				);
			}
		}

		return $result;
	}

	/**
	 * Get all product custom attributes.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	protected static function get_custom_attributes( $like ) {
		$custom_attributes = Cache::remember(
			'wc_custom_attributes',
			array(
				new \Disco\App\Utility\Model,
				'wc_custom_attribute_query',
			),
			array( $like )
		);
		$result            = array();

		if ( is_array( $custom_attributes ) && ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $value ) {
				$product_attr = maybe_unserialize( $value->type );

				if ( ! is_array( $product_attr ) ) {
					continue;
				}

				$result = array_merge( $result, self::filter_product_attributes( $product_attr, $like ) );
			}
		}

		return $result;
	}

	/**
	 * Filter Product Attributes.
	 *
	 * @param array  $product_attr Product Attributes.
	 * @param string $like         Search Term.
	 * @return array
	 */
	protected static function filter_product_attributes( $product_attr, $like ) {
		$filtered_attrs = array();

		foreach ( $product_attr as $key => $arr_value ) {
			if ( strpos( $key, 'pa_' ) !== false ) {
				continue;
			}

			if ( ! empty( $like ) && ( stripos( $arr_value['name'], $like ) === false ) ) {
				continue;
			}

			$filtered_attrs[] = array(
				'id'   => $key,
				'name' => ucwords( str_replace( '-', ' ', $arr_value['name'] ) ),
			);
		}

		return $filtered_attrs;
	}

}
