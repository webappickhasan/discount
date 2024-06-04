<?php //phpcs:ignore

/**
 * Intent Helper Trait.
 *
 * @package    Disco
 * @subpackage \App\Intents\IntentHelper.php
 * @since      1.0.0
 */

namespace Disco\App\Intents;

use Disco\App\Utility\Helper;
use Disco\App\Utility\Settings;

/**
 * This Class contains all the common function for cart related intents,
 * and the intents are Cart, Bulk, Bundle and BOGO
 *
 * @package    Disco
 * @subpackage Disco\App\Intents
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   IntentHelper
 */
trait IntentHelper {//phpcs:ignore

    /**
     * Disco Prepare Intents.
     *
     * @param array $intents          Skip Intents.
     *                                Either a single intent or an array of intents.
     * @return array
     */
	public function prepare_intents( $intents = [] ) {//phpcs:ignore
        $campaigns = ( new \Disco\App\Campaign )->get_campaigns( '1' );

        $new_intents = array();
        $settings    = new \Disco\App\Utility\Settings;

        if ( empty( $campaigns ) ) {
            return $new_intents;
        }

        foreach ( $campaigns as $campaign ) {
            // Check discount expiration date.
            if ( ! Helper::is_in_valid_data( $campaign ) ) {
                continue;
            }

            if ( ! empty( $intents ) ) {
                // Skip the intent if it is in the skip list.
                if ( is_array( $intents ) && ! in_array( $campaign->discount_intent, $intents, true ) ) {
                    continue;
                }
            }

            if ( $campaign->discount_intent === 'BOGO' ) {
                $campaign->discount_intent = 'BuyXGetX';

                if ( in_array( $campaign->bogo_type, array( 'products', 'categories' ), true ) ) {
                    $campaign->discount_intent = 'BuyXGetY';
                }
            }

            $new_intents[] = IntentFactory::get_intent( $campaign, $settings );
        }//end foreach

        return $new_intents;
    }

    /**
     * Get discount applicable items from the cart.
     *
     * @param \WC_Cart                  $cart     Cart Object.
     * @param \Disco\App\Utility\Config $campaign Campaign Config.
     * @return array
     */
    public function get_items_for_discount( $cart, $campaign ) {
        $items = array();

        foreach ( $cart->get_cart() as $item ) {
            $id = $item['product_id'];

            if ( $item['variation_id'] ) {
                $id = $item['variation_id'];
            }

            // Continue if the product is not applicable for the campaign.
            if ( ! $campaign->product_is_applicable( $id ) ) {
                continue;
            }

            // Continue if the item is not passed the filter.
            $product = new \WC_Product( $id );

            $info = array( 'product' => $product );

            if ( ! Helper::is_filter_passed( $campaign, $info ) ) {
                continue;
            }

            $items[] = $item;
        }//end foreach

        return $items;
    }

    /**
     * Verify the rules.
     *
     * @param \Disco\App\Utility\Config $campaign Campaign Config.
     * @param array                     $item     Cart Item.
     * @param array                     $rule     Cart Item.
     * @return bool
     */
    public function verify_rules( $campaign, $item, $rule ) {
        if ( is_object( $rule ) ) {
            $rule = (array) $rule;
        }

        $basis = $this->get_basis_for_item( $item );

        if ( empty( $rule ) ) {
            return false;
        }

        switch ( $campaign->get_discount_intent() ) {
            case 'Product':
            case 'Cart':
                return isset( $rule['discount_value'], $rule['discount_type'] );

            case 'Bulk':
                return $this->verify_bulk_rule( $basis, $rule );

            case 'Bundle':
                return $this->verify_bundle_rule( $basis, $rule );

            case 'BuyXGetX':
                return $this->verify_buyxgetx_rule( $basis, $item, $rule );

            case 'BuyXGetY':
                return $this->verify_buyxgety_rule( $campaign, $basis, $item, $rule );

            default:
                return false;
        }
    }

    /**
     * Get cart item id.
     *
     * @param array $item Cart Item.
     * @return int
     */
    public function get_cart_item_id( $item ) {
        $id = $item['product_id'];

        if ( $item['variation_id'] > 0 ) {
            $id = $item['variation_id'];
        }

        return $id;
    }

    /**
     * Get the basis for cart.
     * Returns the quantity or price of the cart.
     * If the cart is not valid, then return 0.
     *
     * @param \Disco\App\Utility\Config $campaign Discount Rules.
     * @param \WC_Cart                  $cart     Cart .
     * @return float|int
     */
    public function get_basis_for_cart( $campaign, $cart ) {
        if ( ! $cart instanceof \WC_Cart || $cart->is_empty() ) {
            return 0;
        }

        if ( 'cart_quantity' === $campaign->get_discount_based_on() ) {
            return absint( $cart->get_cart_contents_count() );
        }

        return abs( $cart->get_subtotal() );
    }

    /**
     * Get the basis for item.
     * Returns the quantity or price of the item.
     * If the item is not valid, then return 0.
     *
     * @param array|\WC_Product $item Cart Item.
     * @return int
     */
    public function get_basis_for_item( $item ) {
        if ( empty( $item['quantity'] ) ) {
            return 0;
        }

        return absint( $item['quantity'] );
    }

    /**
     * Calculate discount.
     * Returns the discount amount.
     *
     * @param float  $cost           Item Price or Cart Subtotal.
     * @param string $discount_type  Discount Type.
     * @param float  $discount_value Discount Value.
     * @return float
     */
    public function calculate_discount( $cost, $discount_type, $discount_value ) {
        $cost           = (float) $cost;
        $discount_value = (float) $discount_value;

        if ( $discount_type === 'percent' ) {
            $cost = $cost * $discount_value / 100;
        }

        if ( $discount_type === 'fixed' ) {
            $cost = $discount_value;
        }

        return $cost;
    }

    /**
     * Get the discounted price.
     *
     * @param float $cost              Item Price or Cart Subtotal.
     * @param float $discounted_amount Discounted Amount.
     * @return float
     */
    public function discounted_price( $cost, $discounted_amount ) {
        $discounted_price = $cost - $discounted_amount;

        if ( $discounted_price < 0 ) {
            $discounted_price = $cost;
        }

        return $discounted_price;
    }

    /**
     * Check if a number is a multiple of another number for recursive discount.
     *
     * @param float|int $number Number to check.
     * @param float|int $of     Number to check against.
     * @return bool Returns true if the number is a multiple of the given number, false otherwise.
     */
    public function is_multiple( $number, $of ) {
        if ( $of >= $number ) {
            return (float) $of % (float) $number === 0;
        }

        return false;
    }

    /**
     * Check if a product is in a category.
     *
     * @param int   $product_id Product ID.
     * @param array $categories Categories.
     * @return bool
     */
    public function is_in_category( $product_id, $categories ) {
        $terms = get_the_terms( $product_id, 'product_cat' );
        codecept_debug( $terms );

        if ( ! $terms || is_wp_error( $terms ) ) {
            return false;
        }

        $term_ids      = wp_list_pluck( $terms, 'term_id' );
        $common_values = array_intersect( $term_ids, $categories );

        return ! empty( $common_values );
    }

    /**
     * Prepare a discount array for cart.
     * Returns the discount array.
     *
     * @param \Disco\App\Utility\Config $campaign Campaign Config   .
     * @param array                     $items    Cart Item Object.
     * @return array
     */
	public function get_item_discounts( $campaign, $items ) {//phpcs:ignore
        $discounts = array();
        // Get the discount rules.
        $rules = $campaign->get_discount_rules();

        // Check if the cart has valid items.
        if ( ! is_array( $items ) && empty( $items ) ) {
            return $discounts;
        }

        // Loop through the cart items.
        foreach ( $items as $item ) {
            // Get the item object.
            $item_objet = $item['data'];

            // Check if the campaign has rules.
            if ( ! is_array( $rules ) || empty( $rules ) ) {
                continue;
            }

            // Loop through the discount rules.
            foreach ( $rules as $rule ) {
                if ( is_object( $rule ) ) {
                    $rule = (array) $rule;
                }

                // Verify the rule by campaign settings.
                if ( ! $this->verify_rules( $campaign, $item, $rule ) ) {
                    continue;
                }

                $r = md5( microtime() . wp_rand() );

                $id                = $this->get_cart_item_id( $item );
                $discounted_amount = $this->calculate_discount( $item_objet->get_price(), $rule['discount_type'], $rule['discount_value'] );
                $discounted_price  = $this->discounted_price( $item_objet->get_price(), $discounted_amount );

                // Set the discount array.
                $discounts[ $id ]['original_price'] = $item_objet->get_price();
                $discounts[ $id ]['item_key']       = $item['key'];

                // Set Discount Price Array.
                if ( $rule['discount_type'] === 'free' ) {
                    $discounts[ $id ]['prices'][ $r ] = 0;
                } else {
                    $discounts[ $id ]['prices'][ $r ] = $discounted_price;
                }

                $discounts[ $id ]['intent'][ $r ]     = $campaign->get_discount_intent();
                $discounts[ $id ]['get_ids']          = array_values( $rule['get_ids'] );
                $discounts[ $id ]['discounts'][ $r ]  = $discounted_amount;
                $discounts[ $id ]['labels'][ $r ]     = $rule['discount_label'];
                $discounts[ $id ]['quantities'][ $r ] = $rule['get_quantity'];
                $discounts[ $id ]['bogo_qty'][ $r ]   = '';
                $offers                               = $this->get_item_offers( $rule );

                // Calculate the discounted amount and price for BOGO.
                if ( 'free' !== $rule['discount_type'] && $this->is_bogo( $campaign ) ) {
                    $bogo_discounts   = $this->get_items_discount_for_bogo( $id, $discounts, $r, $rule, $item );
                    $discounts[ $id ] = array_merge( $discounts[ $id ], $bogo_discounts );
                }

                $discounts[ $id ]['offers'][ $r ] = $offers;
            }//end foreach
        }//end foreach

        return $discounts;
    }

    /**
     * Prepare bogo discounts for cart items.
     *
     * @param int    $id        Cart Item ID.
     * @param array  $discounts Cart Item Discounts.
     * @param string $r         Discount Rule Key.
     * @param array  $rule      Discount Rule.
     * @param array  $item      Cart Item.
     * @return array
     */
    public function get_items_discount_for_bogo( $id, $discounts, $r, $rule, $item ) {
        if ( empty( $rule['get_quantity'] ) || $rule['get_quantity'] <= 1 ) {
            return array();
        }

        $discounted_amount = $discounts[ $id ]['discounts'][ $r ];

        $discounts[ $id ]['discounts'][ $r ] = $discounted_amount * abs( $rule['get_quantity'] );
        $subtotal                            = abs( $item['line_subtotal'] ) - $discounted_amount;
        $new_price                           = $subtotal / abs( $item['quantity'] );
        $discounts[ $id ]['prices'][ $r ]    = $new_price;

        $discount_str                       = '<del>' . wc_price( $discounts[ $id ]['original_price'] ) . '</del> <ins>' . wc_price( $new_price ) . '</ins>';
        $offer_label                        = $rule['get_quantity'] . ' x ' . $discount_str . '<br/>';
        $offer_label                       .= ( abs( $item['quantity'] ) - abs( $rule['get_quantity'] ) ) . ' x <ins>' . wc_price( $discounts[ $id ]['original_price'] ) . '</ins>';
        $discounts[ $id ]['bogo_qty'][ $r ] = $offer_label;

        return $discounts[ $id ];
    }

    /**
     * Prepare discounts for cart items.
     *
     * @return array|false
     */
    public function prepare_item_discounts() {
        $this->prepare_intents( array( 'Bulk', 'Bundle', 'BOGO' ) );
        // @phpstan-ignore-line
		$cart = WC()->cart;

		if ( empty( $this->intents ) ) {
			return false;
		}

		$discounts = array();

		foreach ( $this->intents as $intent ) {
			$items = $this->get_items_for_discount( $cart, $intent->campaign );
			// @phpstan-ignore-lin

			$get_discounts = $intent->get_discounts( $items, $cart );

			if ( empty( $get_discounts ) ) {
				continue;
			}

			$discounts = $this->merge_discounts( $discounts, $get_discounts );
		}

		return $discounts;
	}

	/**
	 * Is it a bogo campaign.
	 *
	 * @param \Disco\App\Utility\Config $campaign Campaign Config.
	 * @return bool
	 */
	public function is_bogo( $campaign ) {
		return in_array( $campaign->get_discount_intent(), array( 'BuyXGetX', 'BuyXGetY' ), true );
	}

	/**
	 * Check cart is valid or not
	 *
	 * @return bool
	 */
	public function cart_is_valid() {
		return WC()->cart instanceof \WC_Cart && ! WC()->cart->is_empty();
	}

	/**
	 * Get min or max price from an array according to plugin settings.
	 *
	 * @param array $numbers Numbers to compare.
	 * @return float
	 */
	public function min_max_average( $numbers ) {
		$calculation_type = Settings::get( 'min_max_discount_amount' );

		if ( empty( $numbers ) ) {
			return 0.00;
		}

		if ( ! in_array( $calculation_type, array( 'min', 'max', 'average' ), true ) ) {
			$calculation_type = 'min';
		}

		if ( 'average' === $calculation_type ) {
			return (float) array_sum( $numbers ) / count( $numbers );
		}

		if ( 'max' === $calculation_type ) {
			return (float) max( $numbers );
		}

		return (float) min( $numbers );
	}

	/**
	 * Check if the rule is recursive and the basis is not multiple.
	 *
	 * @param float $basis Number to compare.
	 * @param array $rule  Discount Rules.
	 * @return bool
	 */
	public function check_recursive_and_basis( $basis, $rule ) {
		$rule_basis = abs( $rule['min'] );

		if ( $rule['recursive'] === 'yes' && ! $this->is_multiple( $rule_basis, $basis ) ) {
			return true;
		}

		return $rule['recursive'] === 'no' && (float) $basis !== (float) $rule_basis;
	}

	/**
	 * Merges the discounts for cart items.
	 *
	 * @param array $current_discounts The current discounts.
	 * @param array $new_discounts     The new discounts.
	 * @return array The merged discounts.
	 */
	private function merge_discounts( $current_discounts, $new_discounts ) {
		foreach ( $new_discounts as $item_id => $discount ) {
			if ( ! isset( $current_discounts[ $item_id ] ) ) {
				$current_discounts[ $item_id ] = $discount;
			}

			$current_discounts[ $item_id ] = $this->merge_discount_data( $current_discounts[ $item_id ], $discount );
		}

		return $current_discounts;
	}

	/**
	 * Merges the discount data for a single cart item.
	 *
	 * @param array $current_data The current discount data.
	 * @param array $new_data     The new discount data.
	 * @return array The merged discount data.
	 */
	private function merge_discount_data( array $current_data, array $new_data ) {
		if ( empty( $current_data ) ) {
			return $new_data;
		}

		$data = array(
			'prices'         => array_unique( array_merge( $current_data['prices'], $new_data['prices'] ) ),
			'discounts'      => array_merge( $current_data['discounts'], $new_data['discounts'] ),
			'quantities'     => array_merge( $current_data['quantities'], $new_data['quantities'] ),
			'get_ids'        => array_merge( $current_data['get_ids'], $new_data['get_ids'] ),
			'intent'         => array_merge( $current_data['intent'], $new_data['intent'] ),
			'labels'         => array_merge( $current_data['labels'], $new_data['labels'] ),
			'offers'         => array_merge( $current_data['offers'], $new_data['offers'] ),
			'bogo_qty'       => array_merge( $current_data['bogo_qty'], $new_data['bogo_qty'] ),
			'original_price' => $new_data['original_price'],
			'item_key'       => $new_data['item_key'],
		);
		// Note: Consider if you need to recalculate this if 'prices' hasn't changed
		$data['final_price'] = (float) $this->min_max_average( $data['prices'] );
		$data['key']         = array_search( $data['final_price'], $data['prices'], true );

		return $data;
	}

	/**
	 * Get Offer Label.
	 *
	 * @param array $rule Discount Rule.
	 * @return array
	 */
	private function get_item_offers( $rule ) {
		$label    = '';
		$quantity = $rule['min'];

		if ( ! empty( $rule['max'] ) ) {
			$quantity .= ' - ' . $rule['max'];
		}

		if ( 'free' === $rule['discount_type'] ) {
			$label = 'Free';
		}

		if ( 'percent' === $rule['discount_type'] ) {
			$label = $rule['discount_value'] . '% off';
		}

		if ( 'fixed' === $rule['discount_type'] ) {
			$label = wc_price( $rule['discount_value'] ) . ' off';
		}

		if ( ! empty( $rule['get_quantity'] ) ) {
			$quantity = $rule['get_quantity'];
		}

		return array(
			'quantity' => $quantity,
			'label'    => $label,
		);
	}

	/**
	 * Verify Bulk Intent Rules.
	 *
	 * @param float $basis Number to compare.
	 * @param array $rule  Discount Rules.
	 * @return bool
	 */
	private function verify_bulk_rule( $basis, $rule ) {
		return $basis >= $rule['min'] && $basis <= $rule['max'];
	}

	/**
	 * Verify Bundle Intent Rules.
	 *
	 * @param float $basis Number to compare.
	 * @param array $rule  Discount Rules.
	 * @return bool
	 */
	private function verify_bundle_rule( $basis, $rule ) {
		$rule_basis = abs( $rule['min'] );

		if ( $rule['recursive'] === 'yes' && ! $this->is_multiple( $rule_basis, $basis ) ) {
			return false;
		}

		return $rule['recursive'] !== 'no' || (float) $basis === (float) $rule_basis;
	}

	/**
	 * Verify BuyXGetX Intent Rules.
	 *
	 * @param float $basis Number to compare.
	 * @param array $item  Cart Item.
	 * @param array $rule  Discount Rules.
	 * @return bool
	 */
	private function verify_buyxgetx_rule( $basis, $item, $rule ) {//phpcs:ignore
		$verified = ( $basis >= $rule['min'] && $basis <= $rule['max'] );

		$rule_basis = abs( $rule['min'] );

		if ( ! ( $verified & $rule['recursive'] ) === 'yes' && $this->is_multiple( $rule_basis, $basis ) ) {
			$verified = true;
		}

		return $verified;
	}

	/**
	 * Verify BuyXGetY Intent Rules.
	 *
	 * @param \Disco\App\Utility\Config $campaign Campaign Config.
	 * @param float                     $basis    Number to compare.
	 * @param array                     $item     Cart Item.
	 * @param array                     $rule     Discount Rules.
	 * @return bool
	 */
	private function verify_buyxgety_rule( $campaign, $basis, $item, $rule ) {
		if ( ! is_array( $rule['get_ids'] ) ) {
			return false;
		}

		$id = $this->get_cart_item_id( $item );

		if ( 'products' === $campaign->get_bogo_type() && ! in_array( $id, $rule['get_ids'], true ) ) {
			return false;
		}

		if ( 'categories' === $campaign->get_bogo_type() && ! $this->is_in_category( $id, $rule['get_ids'] ) ) {
			return false;
		}

		$verified = ( $basis >= $rule['min'] && $basis <= $rule['max'] );

		$rule_basis = abs( $rule['min'] );

		if ( ! ( $verified & $rule['recursive'] ) === 'yes' && $this->is_multiple( $rule_basis, $basis ) ) {
			$verified = true;
		}

		return $verified;
	}

}
