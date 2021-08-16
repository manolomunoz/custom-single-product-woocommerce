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
	$cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
	if ( is_shop() || is_product_category() ) {
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
		
		
		if ( $custom_products_logo_button != 'true' || $custom_products_button_text != 'true' ) {
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
		if ( $custom_products_button_before != 'true' || $custom_products_button_after != 'true' ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', 'misha_before_after_btn', 10, 3 );
		}
		
	}
}

function misha_before_after_btn( $add_to_cart_html, $product, $args ){
	$cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
	$custom_products_button_before = $cspw_settings_custom_products['custom_products_button_before'];
	$custom_products_button_after  = $cspw_settings_custom_products['custom_products_button_after'];

	$before = '';
	if ( $custom_products_button_before != 'true' ) {
		$before = '<p class="cspw-products-button-before">' . $custom_products_button_before . '</p>';
	}
	$after = '';
	if ( $custom_products_button_after != 'true' ) {
		$after = '<p class="cspw-products-button-after">' . $custom_products_button_after . '</p>';
	}

	return $before . $add_to_cart_html . $after;
}

function cspw_products_custom_button( $args ) {
	global $product;
	$cspw_settings_custom_products        = get_option( 'cspw_settings_custom_products' );
	$custom_products_logo_button = $cspw_settings_custom_products['custom_products_logo_add_cart_button'];
	$custom_products_logo_button   = isset( $cspw_settings_custom_products['custom_products_logo_add_cart_button'] ) ? $cspw_settings_custom_products['custom_products_logo_add_cart_button'] : 'true';
	$custom_products_button_text   = isset( $cspw_settings_custom_products['custom_products_button_text'] ) ? $cspw_settings_custom_products['custom_products_button_text'] : 'true';

	$logo = '';
	if ( $custom_products_logo_button != 'true' ) {
		$logo = '<img class="cspw-products-button-logo" style="width:50px;heigth=50px;" src="' . $custom_products_logo_button . '" />';
	}
	$text = $product->add_to_cart_text();
	if ( $custom_products_button_text != 'true' ) {
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
