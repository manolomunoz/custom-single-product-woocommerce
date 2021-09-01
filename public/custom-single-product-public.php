<?php
/**
 * custom-single-product-public
 *
 * Custom single product template
 *
 * @author   Manuel Muñoz Rodríguez
 * @category public
 * @package  public
 * @version  0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'get_header', 'cspw_custom_single_product_functions' );
/**
 * All functions in single product
 *
 * @return void
 */
function cspw_custom_single_product_functions() {
	$cspw_settings                = get_option( 'cspw_settings' );
	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	if ( is_product() ) {
		// ****************** MOSTRAR PRODUCTO ******************
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

		// ****************** MOSTRAR PRODUCTO PERSONALIZADO ******************
		$custom_logo_add_cart_button        = isset( $cspw_settings_custom_product['custom_logo_add_cart_button'] ) ? $cspw_settings_custom_product['custom_logo_add_cart_button'] : 'true';
		$custom_text_add_cart_button        = isset( $cspw_settings_custom_product['custom_text_add_cart_button'] ) ? $cspw_settings_custom_product['custom_text_add_cart_button'] : 'true';
		$custom_text_before_add_cart_button = isset( $cspw_settings_custom_product['custom_text_before_add_cart_button'] ) ? $cspw_settings_custom_product['custom_text_before_add_cart_button'] : 'true';
		$custom_text_after_add_cart_button  = isset( $cspw_settings_custom_product['custom_text_after_add_cart_button'] ) ? $cspw_settings_custom_product['custom_text_after_add_cart_button'] : 'true';
		$custom_price_before                = isset( $cspw_settings_custom_product['custom_price_before'] ) ? $cspw_settings_custom_product['custom_price_before'] : 'true';
		$custom_price_after                 = isset( $cspw_settings_custom_product['custom_price_after'] ) ? $cspw_settings_custom_product['custom_price_after'] : 'true';
		$custom_image_zoom                  = isset( $cspw_settings_custom_product['custom_image_zoom'] ) ? $cspw_settings_custom_product['custom_image_zoom'] : 'true';
		$custom_show_tabs                   = isset( $cspw_settings_custom_product['custom_show_tabs'] ) ? $cspw_settings_custom_product['custom_show_tabs'] : 'true';
		$custom_new_tab                     = isset( $cspw_settings_custom_product['custom_new_tab'] ) ? $cspw_settings_custom_product['custom_new_tab'] : 'true';
		$custom_show_related_product_button = isset( $cspw_settings_custom_product['custom_show_related_product_button'] ) ? $cspw_settings_custom_product['custom_show_related_product_button'] : 'true';
		$custom_show_product_custom_comment = isset( $cspw_settings_custom_product['custom_show_product_custom_comment'] ) ? $cspw_settings_custom_product['custom_show_product_custom_comment'] : 'true';

		if ( $custom_show_product_custom_comment == 3 || $custom_show_product_custom_comment == 2  ) {
			add_filter( 'get_comment_author', 'cspw_product_custom_user_name_comment', 10, 3 );
		}
		if ( $custom_show_related_product_button == 1 ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}
		if ( strlen($custom_new_tab) > 0 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_new_product_tab' );
		}
		if ( $custom_show_tabs == 'lista' ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			require_once plugin_dir_path( __FILE__ ) . 'single-product/tabs.php';
		}
		if ( $custom_image_zoom == 1 ) {
			add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );
		}
		if ( $custom_price_after != '' || $custom_price_before != '' ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			add_action( 'woocommerce_single_product_summary', 'cspw_change_product_price_display' );
		}
		if ( $custom_logo_add_cart_button != '' || $custom_text_add_cart_button != '' || $custom_text_before_add_cart_button != '' || $custom_text_after_add_cart_button != '' ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			require_once plugin_dir_path( __FILE__ ) . 'single-product/add-to-cart.php';
		}
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
			add_filter( 'woocommerce_product_tabs', 'cspw_remove_product_tab_aditional', 98 );
		}
		if ( $tab_description == 1 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_remove_product_tab_description', 98 );
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
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
		if ( $image == 1 ) {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		}
		if ( $related_product == 1 ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
		
	}
}

/**
 * Custom author comment in single product
 *
 * @param [type] $author author this comment.
 * @param [type] $comment_id id this comment.
 * @param [type] $comment string comment.
 * @return object return the author name.
 */
function cspw_product_custom_user_name_comment( $author, $comment_id, $comment ) {
	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );

	$custom_show_product_custom_comment = isset( $cspw_settings_custom_product['custom_show_product_custom_comment'] ) ? $cspw_settings_custom_product['custom_show_product_custom_comment'] : 'true';

	$firstname   = '';
	$lastname    = '';
	$author_name = $comment->comment_author;

	if ( $author_name ) {
		$nombre_partes = explode( ' ', $author_name );
		$firstname     = $nombre_partes[0];
		$lastname      = $nombre_partes[1];
		if ( $custom_show_product_custom_comment == 3 ) {
			if ( $lastname ) {
				$custom_lastname = substr( $lastname, 0, 1 );
				$author          = $firstname . ' ' . $custom_lastname . '.';
			} else {
				$author = $firstname;
			}
		}
		if ( $custom_show_product_custom_comment == 2 ) {
			$custom_firstname = substr( $firstname, 0, 1 );
			$author           = $custom_firstname . '. ' . $lastname;
		}
	}

	return $author;
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
function cspw_remove_product_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['additional_information'] );
	unset( $tabs['reviews'] );
	return $tabs;
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

function cspw_change_product_price_display() {
	global $product;

	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	$before_text   = '';
	$after_text    = '';
	if ( strlen( $cspw_settings_custom_product['custom_price_before'] ) > 0 ) {
		$before_text = '<p class="before-price" >' . $cspw_settings_custom_product['custom_price_before'] . '</p>';
	}
	if ( strlen( $cspw_settings_custom_product['custom_price_after'] ) > 0 ) {
		$after_text = '<p class="after-price" >' . $cspw_settings_custom_product['custom_price_after'] . '</p>';
	}
	$new_price = '<div class="cspw-product-container-price">' . $before_text . ' <p class="price">' . $product->get_price() . '</p> ' . $after_text . '</div>';
	echo $new_price;
}

function cspw_new_product_tab( $tabs ) {
	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	$custom_new_tab               = $cspw_settings_custom_product['custom_new_tab'];

	$tabs['cspw_tab'] = array(
		'title'     => $custom_new_tab,
		'priority'  => 50,
		'callback'  => 'cspw_new_product_tab_content'
	);

	return $tabs;
}

function cspw_new_product_tab_content() {
	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	$custom_new_tab_content       = isset( $cspw_settings_custom_product['custom_new_tab_content'] ) ? $cspw_settings_custom_product['custom_new_tab_content'] : 'true';
	if ( $custom_new_tab_content != 'true' ) {
		echo $custom_new_tab_content;
	}
	
}
