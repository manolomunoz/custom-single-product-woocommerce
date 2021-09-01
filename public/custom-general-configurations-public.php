<?php
/**
 * Custom general configurations public
 *
 * Custom single product template
 *
 * @author   Manuel Muñoz Rodríguez
 * @category Functions
 * @package  WordPress
 * @version  0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'get_header', 'cspw_general_config_products_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_general_config_products_functions() {
	$cspw_settings_init = get_option( 'cspw_settings_init' );
}
