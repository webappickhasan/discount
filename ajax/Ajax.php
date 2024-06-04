<?php
/**
 * Disco
 *
 * @package   Disco
 * @author    Ohidul Islam <wahid0003@gmail.com>
 * @copyright 2022 WebAppick
 * @license   GPL 2.0+
 * @link      http://domain.tld
 */

namespace Disco\Ajax;

use Disco\Engine\Base;

/**
 * AJAX in the public
 */
class Ajax extends Base {

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize() {
        if ( ! \apply_filters( 'disco_d_ajax_initialize', true ) ) {
            return;
        }

		// For not logged user.
		\add_action( 'wp_ajax_nopriv_your_method', array( $this, 'your_method' ) );
	}

	/**
	 * The method to run on ajax
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function your_method() {
		$return = array(
			'message' => 'Saved',
			'ID'      => 1,
		);

		\wp_send_json_success( $return );
	}

}
