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
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d_debug = new WPBP_Debug( 'Disco' );//phpcs:ignore

/**
 * Log text inside the debugging plugins.
 *
 * @param string $text The text.
 * @return void
 */
function disco_log( string $text ) {
	global $d_debug;
	$d_debug->log( $text );
}
