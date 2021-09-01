<?php
/**
 * Add to cart
 *
 * Custom add to cart button in single product
 *
 * @author   Manuel Muñoz <mmr010496@gmail.com>
 * @category WordPress
 * @package  WordPress
 * @version  0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'woocommerce_single_product_summary', function() {
	global $product;

	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );

	if ( ! $product->is_purchasable() ) {
		return;
	}

	echo wc_get_stock_html( $product ); // WPCS: XSS ok.

	if ( $product->is_in_stock() ) : ?>

		<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

		<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<?php
			do_action( 'woocommerce_before_add_to_cart_quantity' );
			if ( isset( $cspw_settings_custom_product['custom_text_before_add_cart_button'] ) ) {
				$before_text = '<p class="cspw-custom-text-before-add-cart-button" >' . $cspw_settings_custom_product['custom_text_before_add_cart_button'] . '</p>';
				echo $before_text;
			}

			woocommerce_quantity_input(
				array(
					'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
					'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
					'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				)
			);

			do_action( 'woocommerce_after_add_to_cart_quantity' );
			?>

			<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
			<?php
			if ( $cspw_settings_custom_product['custom_logo_add_cart_button'] != '' ) {
				$logo = '<img class="cspw-custom-logo-add-cart-button" style="width:50px;heigth=50px;" src="' . $cspw_settings_custom_product['custom_logo_add_cart_button'] . '" />';
				echo $logo;
			}
			if ( $cspw_settings_custom_product['custom_text_add_cart_button'] != '' ) {
				$text = $cspw_settings_custom_product['custom_text_add_cart_button'];
				echo $text;
			} else {
				echo esc_html( $product->single_add_to_cart_text() );
			}
			
			?>
			</button>

			<?php 
			do_action( 'woocommerce_after_add_to_cart_button' );
			if ( isset( $cspw_settings_custom_product['custom_text_after_add_cart_button'] ) ) {
				$after_text = '<p class="cspw-custom-text-after-add-cart-button" >' . $cspw_settings_custom_product['custom_text_after_add_cart_button'] . '</p>';
				echo $after_text;
			}
			?>
		</form>

		<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

	<?php endif;

}, 30 );
