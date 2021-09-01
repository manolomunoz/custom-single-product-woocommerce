<?php
/**
 * Custom products loop public
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

add_action( 'get_header', 'cspw_custom_products_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_custom_products_functions() {
	$cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
	if ( woocommerce_product_loop() ) {
		$results                       = isset( $cspw_settings_custom_products['results'] ) ? $cspw_settings_custom_products['results'] : 'true';
		$order                         = isset( $cspw_settings_custom_products['order'] ) ? $cspw_settings_custom_products['order'] : 'true';
		$loop_title                    = isset( $cspw_settings_custom_products['loop_title'] ) ? $cspw_settings_custom_products['loop_title'] : 'true';
		$loop_price                    = isset( $cspw_settings_custom_products['loop_price'] ) ? $cspw_settings_custom_products['loop_price'] : 'true';
		$loop_image                    = isset( $cspw_settings_custom_products['loop_image'] ) ? $cspw_settings_custom_products['loop_image'] : 'true';
		$loop_add_cart_button          = isset( $cspw_settings_custom_products['loop_add_cart_button'] ) ? $cspw_settings_custom_products['loop_add_cart_button'] : 'true';
		$custom_products_button_before = isset( $cspw_settings_custom_products['custom_products_button_before'] ) ? $cspw_settings_custom_products['custom_products_button_before'] : 'true';
		$custom_products_button_after  = isset( $cspw_settings_custom_products['custom_products_button_after'] ) ? $cspw_settings_custom_products['custom_products_button_after'] : 'true';
		$custom_products_logo_button   = isset( $cspw_settings_custom_products['custom_products_logo_add_cart_button'] ) ? $cspw_settings_custom_products['custom_products_logo_add_cart_button'] : 'true';
		$custom_products_button_text   = isset( $cspw_settings_custom_products['custom_products_button_text'] ) ? $cspw_settings_custom_products['custom_products_button_text'] : 'true';
		
		$cspw_settings_init = get_option( 'cspw_settings_init' );
			
		if ( strlen($custom_products_logo_button) > 0 || strlen($custom_products_button_text) > 0 ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			add_action( 'woocommerce_after_shop_loop_item', 'cspw_products_custom_button' );
		}
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
		if ( strlen($custom_products_button_before) > 0 || strlen($custom_products_button_after) > 0 ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', 'cspw_before_after_btn', 10, 3 );
		}
		
	}
}

/**
 * Add text after and before add to cart button
 *
 * @param string $add_to_cart_html the add to cart button html.
 * @param object $product product actual object.
 * @param array  $args array arguments to product.
 * @return string return the new add to cart html
 */
function cspw_before_after_btn( $add_to_cart_html, $product, $args ){
	$cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
	$custom_products_button_before = $cspw_settings_custom_products['custom_products_button_before'];
	$custom_products_button_after  = $cspw_settings_custom_products['custom_products_button_after'];

	$before = '';
	if ( strlen($custom_products_button_before) > 0 ) {
		$before = '<p class="cspw-products-button-before">' . $custom_products_button_before . '</p>';
	}
	$after = '';
	if ( strlen($custom_products_button_after) > 0 ) {
		$after = '<p class="cspw-products-button-after">' . $custom_products_button_after . '</p>';
	}

	return $before . $add_to_cart_html . $after;
}

/**
 * Custom add to cart button in loop products
 *
 * @param array $args arguments to product
 * @return void
 */
function cspw_products_custom_button( $args ) {
	global $product;
	$cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
	$custom_products_logo_button   = $cspw_settings_custom_products['custom_products_logo_add_cart_button'];
	$custom_products_logo_button   = isset( $cspw_settings_custom_products['custom_products_logo_add_cart_button'] ) ? $cspw_settings_custom_products['custom_products_logo_add_cart_button'] : 'true';
	$custom_products_button_text   = isset( $cspw_settings_custom_products['custom_products_button_text'] ) ? $cspw_settings_custom_products['custom_products_button_text'] : 'true';

	$logo = '';
	if ( $custom_products_logo_button != 'true' ) {
		$logo = '<img class="cspw-products-button-logo" style="width:50px;heigth=50px;" src="' . $custom_products_logo_button . '" />';
	}
	$text = $product->add_to_cart_text();
	if ( strlen($custom_products_button_text) > 0 ) {
		$text = $custom_products_button_text;
	}

	echo apply_filters(
		'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			$logo . esc_html( $text )
		),
		$product,
		$args
	);
}
