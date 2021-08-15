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

add_action( 'get_header', 'cspw_custom_products_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_custom_products_functions() {
	$cspw_settings   = get_option( 'cspw_settings' );
	if ( is_product() ) {
		$title           = isset( $cspw_settings['title'] ) ? $cspw_settings['title'] : 'true';
		$show_sku        = isset( $cspw_settings['sku'] ) ? $cspw_settings['sku'] : 'true';
		$categories      = isset( $cspw_settings['categories'] ) ? $cspw_settings['categories'] : 'true';
		$excerpt         = isset( $cspw_settings['excerpt'] ) ? $cspw_settings['excerpt'] : 'true';
		$price           = isset( $cspw_settings['price'] ) ? $cspw_settings['price'] : 'true';
		$image           = isset( $cspw_settings['image'] ) ? $cspw_settings['image'] : 'true';
		$add_cart_button = isset( $cspw_settings['add_cart_button'] ) ? $cspw_settings['add_cart_button'] : 'true';
		$tab_description = isset( $cspw_settings['tab_description'] ) ? $cspw_settings['tab_description'] : 'true';
		$tab_aditional   = isset( $cspw_settings['tab_aditional'] ) ? $cspw_settings['tab_aditional'] : 'true';
		$tab_reviews     = isset( $cspw_settings['tab_reviews'] ) ? $cspw_settings['tab_reviews'] : 'true';
		$related_product = isset( $cspw_settings['related_product'] ) ? $cspw_settings['related_product'] : 'true';

		if ( $title == 1 ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		}
		if ( $show_sku == 1 ) {
			add_filter( 'wc_product_sku_enabled', 'cspw_remove_product_page_skus' );
		}
		if ( $categories == 1 ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}
		if ( $tab_aditional == 1 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_remove_product_tab_description', 98 );
		}
		if ( $tab_description == 1 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_remove_product_tab_aditional', 98 );
		}
		if ( $tab_reviews == 1 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_remove_product_tab_reviews', 98 );
		}
		if ( $excerpt == 1 ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}
		if ( $price == 1 ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		}
		if ( $add_cart_button == 1 ) {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		}
		if ( $image == 1 ) {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		}
		if ( $related_product == 1 ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
		
	}
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

/**
 * Remove SKU in single product
 *
 * @return false return false
 */
function cspw_remove_product_page_skus() {
	return false;
}


/**
 * Remove tabs in single product
 *
 * @param [type] $tabs
 * @return void
 */
function cspw_remove_product_tab_description( $tabs ) {
    unset( $tabs['description'] );
    return $tabs;
}

/**
 * Remove tabs in single product
 *
 * @param [type] $tabs
 * @return void
 */
function cspw_remove_product_tab_aditional( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}

  /**
 * Remove tabs in single product
 *
 * @param [type] $tabs
 * @return void
 */
function cspw_remove_product_tab_reviews( $tabs ) {
	unset( $tabs['reviews'] );
	return $tabs;
}
