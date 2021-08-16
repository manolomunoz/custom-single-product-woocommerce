<?php
/**
 * class-custom-products-loop-public
 *
 * Custom single product template
 *
 * @author   Manuel Muñoz Rodríguez
 * @category public
 * @package  public
 * @version  0.1
 */


add_action( 'get_header', 'cspw_custom_products_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_custom_products_functions() {
	$cspw_settings   = get_option( 'cspw_settings' );
	if ( is_shop() || is_product_category() ) {
		$results              = isset( $cspw_settings['results'] ) ? $cspw_settings['results'] : 'true';
		$order                = isset( $cspw_settings['order'] ) ? $cspw_settings['order'] : 'true';
		$loop_title           = isset( $cspw_settings['loop_title'] ) ? $cspw_settings['loop_title'] : 'true';
		$loop_price           = isset( $cspw_settings['loop_price'] ) ? $cspw_settings['loop_price'] : 'true';
		$loop_image           = isset( $cspw_settings['loop_image'] ) ? $cspw_settings['loop_image'] : 'true';
		$loop_add_cart_button = isset( $cspw_settings['loop_add_cart_button'] ) ? $cspw_settings['loop_add_cart_button'] : 'true';
		
		if ( $results == 1 ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}
		if ( $order == 1 ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}
		if ( $loop_title == 1 ) {
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		}
		if ( $loop_price == 1 ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		}
		if ( $loop_image == 1 ) {
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		}
		if ( $loop_add_cart_button == 1 ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
		}
		
	}
}
