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
		$cspw_settings   = get_option( 'cspw_settings' );
		$show_sku        = isset( $cspw_settings['sku'] ) ? $cspw_settings['sku'] : 'true';
		$categories      = isset( $cspw_settings['categories'] ) ? $cspw_settings['categories'] : 'true';
		$tab_description = isset( $cspw_settings['categories'] ) ? $cspw_settings['tab_description'] : 'true';
		$tab_aditional   = isset( $cspw_settings['categories'] ) ? $cspw_settings['tab_aditional'] : 'true';
		$tab_reviews     = isset( $cspw_settings['categories'] ) ? $cspw_settings['tab_reviews'] : 'true';

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
