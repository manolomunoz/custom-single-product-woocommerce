<?php
/**
 * class-custom-single-product-public
 *
 * Custom single product template
 *
 * @author   Manuel Muñoz Rodríguez
 * @category public
 * @package  public
 * @version  0.1
 */

add_action( 'get_header', 'cspw_single_product_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_single_product_functions() {
	if ( is_product() ) {
		$cspw_settings = get_option( 'cspw_settings' );
		$show_sku      = isset( $cspw_settings['sku'] ) ? $cspw_settings['sku'] : 'true';

		if ( $show_sku == 1 ) {
			add_filter( 'wc_product_sku_enabled', 'cspw_remove_product_page_skus' );
		}
	}
}


function cspw_remove_product_page_skus() {
	return false;
}
