<?php
/**
 * Search Utility
 *
 * @package    Disco
 * @subpackage App\Utility
 * @since      1.0.0
 * @category   Utility
 */

namespace Disco\App\Utility;

use WC_Cart;

/**
 * Class Helper
 *
 * @package    Disco
 * @subpackage App\Utility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Utility
 */
class Helper {

	/**
	 * Get Cart Info.
     *
     * @param \WC_Cart $cart    Cart Object.
     * @param string   $item_id Item ID.
     * @return array
     */
    public static function cart_info( $cart, $item_id = '' ) {
        $cart_info = array();

        if ( $cart instanceof WC_Cart ) {
            $cart_info['total']['line_items_count']    = $cart->get_cart_contents_count();
            $cart_info['total']['cart_items_quantity'] = array_sum( $cart->get_cart_item_quantities() );
            $cart_info['total']['cart_total_weight']   = $cart->get_cart_contents_weight();
            $cart_info['total']['cart_subtotal']       = $cart->get_subtotal();

            foreach ( $cart->get_cart() as $item ) {
                $id = $item['product_id'];

                if ( $item['variation_id'] ) {
                    $id = $item['variation_id'];
                }

                $item_info = array(
                    'product_id'                 => $item['product_id'],
                    'variation_id'               => $item['variation_id'],
                    'cart_line_item_quantity'    => $item['quantity'],
                    'cart_line_item_subtotal'    => $item['line_subtotal'],
                    'cart_line_item_id'          => $item['data']->get_id(),
                    'cart_line_item_sku'         => $item['data']->get_sku(),
                    'cart_line_item_title'       => $item['data']->get_name(),
                    'cart_line_item_attributes'  => $item['data']->get_attributes(),
                    'cart_line_item_category'    => wp_strip_all_tags( $item['data']->get_categories() ),
                    'cart_line_item_tags'        => $item['data']->get_tags(),
                    'cart_line_item_weight_unit' => $item['data']->get_meta( '_unit' ),
                    'cart_line_item_weight'      => $item['data']->get_weight(),
                    'cart_line_item_width'       => $item['data']->get_width(),
                    'cart_line_item_height'      => $item['data']->get_height(),
                    'cart_line_item_length'      => $item['data']->get_length(),
                    'cart_line_item_type'        => $item['data']->get_type(),
                    'product'                    => $item['data'],
                );

                if ( $item_id && $item_id === $id ) {
                    return $item_info;
                }

                $cart_info['items'][ $id ] = $item_info;
            }//end foreach
        }//end if

        return $cart_info;
    }

    /**
     * Check product discount has any date limit.
     *
     * @param \Disco\App\Utility\Config $config Campaign Configuration.
     * @return bool
     */
    public static function is_in_valid_data( $config ) {
        if ( empty( $config->discount_valid_from ) && empty( $config->discount_valid_to ) ) {
            return true;
        }

        if ( isset( $config->discount_valid_from, $config->discount_valid_to )
            && strtotime( $config->discount_valid_from )
            && strtotime( $config->discount_valid_to )
        ) {
            $today      = gmdate( 'Y-m-d H:i:s' );
            $start_date = gmdate( 'Y-m-d H:i:s', strtotime( $config->discount_valid_from ) );
            $end_date   = gmdate( 'Y-m-d H:i:s', strtotime( $config->discount_valid_to ) );

            return ( $today >= $start_date ) && ( $today <= $end_date );
        }

        return false;
    }

    /**
     * Check if the product is passed the filter
     *
     * @param \Disco\App\Utility\Config $config  Campaign Configuration.
     * @param array                     $product Product Info.
     * @return bool
     */
    public static function is_filter_passed( $config, $product ) {
        return ( new Filter( $config, $product ) )->is_passed();
    }

}
