<?php

/**
 * Filter Utility
 *
 * @package    Disco
 * @subpackage App\Utility
 * @since      1.0.0
 * @category   Utility
 */

namespace Disco\App\Utility;

use Disco\App\Attributes\AttributeFactory;

/**
 * Class Filter
 *
 * @package    Disco
 * @subpackage App\Utility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Utility
 */
class Filter {

    /**
     * @var array $product Product Info.
     */
    private $product;

    /**
     * Contains the conditions array.
     *
     * @var array $conditions Filter object.
     */
    private $conditions;

    /**
     * Filter constructor.
     *
     * @param \Disco\App\Utility\Config $config  Campaign configuration.
     * @param array                     $product Product Object.
     */
    public function __construct( $config, $product ) {
        $this->conditions = $config->get_conditions();
        $this->product    = $product;
    }

    /**
     * Check if the product is passed the filter
     *
     * @return bool
     */
    public function is_passed() {
        if ( ! empty( $this->conditions ) ) {
            return $this->check_all_conditions( $this->conditions );
        }

        return true;
    }

    /**
     * Check if the product is passed the filter
     *
     * @param array $filters Filter array.
     *                       array(
     *                       array(
     *                       'base_operator' => 'and',
     *                       'base_filters'  => array(
     *                       0 => array(
     *                       'filter'    => 'id',
     *                       'operator'  => 'and',
     *                       'condition' => 'between',
     *                       'compare'   => array( 1, 100 ),
     *                       ),
     *                       1 => array(
     *                       'filter'    => 'Hat Wizard',
     *                       'operator'  => 'and',
     *                       'condition' => 'contain',
     *                       'compare'   => 'Hat',
     *                       ),
     *                       ),
     *                       ),
     *                       array(
     *                       'base_operator' => 'or',
     *                       'base_filters'  => array(
     *                       0 => array(
     *                       'filter'    => 'total_sold',
     *                       'operator'  => 'and',
     *                       'condition' => 'greater_tan',
     *                       'compare'   => 2000,
     *                       ),
     *                       1 => array(
     *                       'filter'    => 'total_order_name',
     *                       'operator'  => 'or',
     *                       'condition' => 'equal',
     *                       'compare'   => '100',
     *                       ),
     *                       ),
     *                       array(
     *                       'base_operator' => 'and',
     *                       'base_filters'  => array(
     *                       0 => array(
     *                       'filter'    => 'total_sold',
     *                       'operator'  => 'and',
     *                       'condition' => 'greater_tan',
     *                       'compare'   => 2000,
     *                       ),
     *                       2 => array(
     *                       'filter'    => 'title',
     *                       'operator'  => 'and',
     *                       'condition' => 'not_equal',
     *                       'compare'   => '',
     *                       ),
     *                       ),
     *                       ),
     *                       ),.
     * @return bool
     */
	public function check_group_conditions( $filters ) {// phpcs:ignore

        $result = true;
        // Start with true, will be updated based on conditions
		$first = true;

		foreach ( $filters as $filter ) {
			if ( is_array( $filter ) ) {
				$filter = (object) $filter;
			}

			if ( strtolower( $filter->operator ) ) {
				$operator = strtolower( $filter->operator );
			} else {
				$operator = 'and';
			}

			$condition = $this->compare_value( $filter );

			// Apply the operator
			if ( $first ) {
				$result = $condition;
				$first  = false;
			} elseif ( $operator === 'and' ) {
				$result = $result && $condition;// phpcs:ignore
			} elseif ( $operator === 'or' ) {
				$result = $result || $condition;// phpcs:ignore
			}
		}//end foreach

		return $result;
	}

	/**
	 * Compare value based on a type
	 *
	 * @param object $filter Filter object.
	 * @return bool
	 */
	public function compare_value( $filter ) {// phpcs:ignore

		// Filter should be an object. If not, return false
		if ( ! is_object( $filter ) ) {
			return false;
		}

		if ( ! is_object( $filter ) || ! isset( $filter->condition, $filter->compare ) ) {
			return false;
		}

		$condition = $filter->condition;
		$compare   = $filter->compare;

		// Get $compare variable value  type
		$type = gettype( $compare );

		// If $compare is a number, check if it is an integer or double
		if ( is_numeric( $type ) ) {
			$type = 'integer';
		}

		// If $compare is a date, check if it is a valid date
		if ( strtotime( $type ) ) {
			$type = 'date';
		}

		// If $condition is an array, check if it is an array of strings
		if ( in_array( $condition, array( 'in_list', 'not_in_list' ), true ) ) {
			$type = 'array';
		}

		// If $compare is an array, check if it is a number range
		if ( is_array( $compare ) && count( $compare ) === 2 && is_numeric( $compare[0] ) && is_numeric( $compare[1] ) ) {
			$type = 'integer';
		}

		// If $compare is an array, check if it is a date range
		if ( is_array( $compare ) && count( $compare ) === 2 && strtotime( $compare[0] ) && strtotime( $compare[1] ) ) {
			$type = 'date';
		}

		// Compare value based on a type.
		switch ( $type ) {
			case 'date':
				return $this->date_compare( $filter );

			case 'array':
				return $this->array_compare( $filter );

			case 'double':
			case 'integer':
				return $this->number_compare( $filter );

			default:
				return $this->string_compare( $filter );
		}
	}

	/**
	 * Compare value for a date type.
	 *
	 * @param object $filter Filter object.
	 * @return bool
	 */
	public function date_compare( $filter ) {
		if ( ! is_object( $filter ) || ! isset( $filter->condition, $filter->compare, $filter->compare_with ) ) {
			return false;
		}

		$condition    = $filter->condition;
		$compare      = $filter->compare;
		$compare_with = AttributeFactory::get_value( $filter->compare_with, $this->product );

		switch ( $condition ) {
			case 'equal':
				return $compare === $compare_with;

			case 'not_equal':
				return $compare !== $compare_with;

			case 'greater':
				return $compare_with > $compare;

			case 'greater_equal':
				return $compare_with >= $compare;

			case 'lesser':
				return $compare_with < $compare;

			case 'lesser_equal':
				return $compare_with <= $compare;

			case 'between':
				return $compare_with >= $compare[0] && $compare_with <= $compare[1];

			default:
				return false;
		}//end switch
	}

	/**
	 * Compare value for an array type.
	 *
	 * @param object $filter Filter object.
	 * @return bool
	 */
	public function array_compare( $filter ) {// phpcs:ignore
		if ( ! is_object( $filter ) || ! isset( $filter->condition, $filter->compare, $filter->compare_with ) ) {
			return false;
		}

		$condition    = $filter->condition;
		$compare      = $filter->compare;
		$compare_with = AttributeFactory::get_value( $filter->compare_with, $this->product );

		switch ( $condition ) {
			case 'not_in_list':
				if ( is_array( $compare ) && is_array( $compare_with ) ) {
					return ! count( array_intersect( $compare, $compare_with ) );
				}

				if ( is_array( $compare_with ) ) {
					return ! in_array( $compare, $compare_with, true );
				}

				if ( is_array( $compare ) && is_string( $compare_with ) ) {
					return ! in_array( $compare_with, $compare, true );
				}

				return false;

			case 'in_list':
				if (  is_array( $compare ) && isset($compare[0]) && is_array( $compare_with ) ) {
					return (bool)  count( array_intersect( $compare[0], $compare_with ) );
				}

				if ( is_array( $compare_with ) && is_string( $compare ) ) {
					return in_array( $compare, $compare_with, true );
				}

				if ( is_array( $compare ) && is_string( $compare_with ) ) {
					return in_array( $compare_with, $compare, true );
				}

				return false;

			default:
				return false;
		}//end switch
	}

	/**
	 * Compare value for a number type.
	 *
	 * @param object $filter Filter object.
	 * @return bool
	 */
	public function number_compare( $filter ) {// phpcs:ignore
		if ( ! is_object( $filter ) || ! isset( $filter->condition, $filter->compare, $filter->compare_with ) ) {
			return false;
		}

		$condition    = $filter->condition;
		$compare      = $filter->compare;
		$compare_with = AttributeFactory::get_value( $filter->compare_with, $this->product );

		switch ( $condition ) {
			case 'equal':
				return $compare === $compare_with;

			case 'not_equal':
				return $compare !== $compare_with;

			case 'greater':
				return $compare_with > $compare;

			case 'greater_equal':
				return $compare_with >= $compare;

			case 'lesser':
				return $compare_with < $compare;

			case 'lesser_equal':
				return $compare_with <= $compare;

			case 'between':
				return $compare_with >= $compare[0] && $compare_with <= $compare[1];

			default:
				return false;
		}//end switch
	}

	/**
	 * Compare value for a string type.
	 *
	 * @param object $filter Filter object.
	 * @return bool
	 */
	public function string_compare( $filter ) {
		if ( ! is_object( $filter ) || ! isset( $filter->condition, $filter->compare, $filter->compare_with ) ) {
			return false;
		}

		$condition    = $filter->condition;
		$compare      = $filter->compare;
		$compare_with = AttributeFactory::get_value( $filter->compare_with, $this->product );

		switch ( $condition ) {
			case 'equal':
				return $compare === $compare_with;

			case 'not_equal':
				return $compare !== $compare_with;

			case 'contain':
				return strpos( $compare_with, $compare ) !== false;

			case 'not_contain':
				return strpos( $compare, $compare_with ) === false;

			case 'start_with':
				return strpos( $compare_with, $compare ) === 0;

			case 'end_with':
				return strpos( $compare_with, $compare ) === strlen( $compare_with ) - strlen( $compare );

			default:
				return false;
		}//end switch
	}

	/**
	 * @param array $filters Filter array.
	 * @return bool
	 */
	public function check_all_conditions( $filters ) {
		$final_result = true;
		$first_group  = true;

		if ( empty( $filters ) ) {
			return true;
		}

		foreach ( $filters as $group ) {
			if ( is_array( $group ) ) {
				$group = (object) $group;
			}

			$group_result = $this->check_group_conditions( $group->base_filters );

			if ( $first_group ) {
				$final_result = $group_result;
				$first_group  = false;
			} elseif ( $group->base_operator === 'and' ) {
				$final_result = $final_result && $group_result; // phpcs:ignore
			} elseif ( $group->base_operator === 'or' ) {
				$final_result = $final_result || $group_result; // phpcs:ignore
			}
		}

		return $final_result;
	}

}
