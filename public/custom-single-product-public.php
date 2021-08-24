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
	$cspw_settings                  = get_option( 'cspw_settings' );
	$cspw_settings_custom_product   = get_option( 'cspw_settings_custom_product' );
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

		if ( $custom_show_related_product_button == 1 ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}
		if ( strlen($custom_new_tab) > 0 ) {
			add_filter( 'woocommerce_product_tabs', 'cspw_new_product_tab' );
		}
		if ( $custom_show_tabs == 'lista' ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action( 'woocommerce_after_single_product_summary', 'cspw_show_tabs_list', 5 );
		}
		if ( $custom_image_zoom == 1 ) {
			add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );
		}
		if ( $custom_price_after != '' || $custom_price_before != '' ) {
			add_filter( 'woocommerce_get_price_html', 'cspw_change_product_price_display' );
		}
		if ( $custom_logo_add_cart_button != '' || $custom_text_add_cart_button != '' || $custom_text_before_add_cart_button != '' || $custom_text_after_add_cart_button != '' ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			add_action( 'woocommerce_single_product_summary', 'cspw_product_custom_add_cart_button', 30 );
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

/**
 * Custom add to cart button
 *
 * @return void
 */
function cspw_product_custom_add_cart_button() {
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
}

function cspw_change_product_price_display( $price ) {
	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	$before_text   = '';
	$after_text    = '';
	if ( isset( $cspw_settings_custom_product['custom_price_before'] ) ) {
		$before_text = '<p class="cspw-custom-text-before-price" >' . $cspw_settings_custom_product['custom_price_before'] . '</p>';
	}
	if ( isset( $cspw_settings_custom_product['custom_price_after'] ) ) {
		$after_text = '<p class="cspw-custom-text-after-price" >' . $cspw_settings_custom_product['custom_price_after'] . '</p>';
	}
	$new_price = '<div class="cspw-product-container-price">' . $before_text . ' ' . $price . ' ' . $after_text . '</div>';
	return $new_price;
}

/**
 * Show tabs in list
 *
 * @return void
 */
function cspw_show_tabs_list() {
	global $product;
	$cspw_settings   = get_option( 'cspw_settings' );
	$tab_description = $cspw_settings['tab_description'];
	$tab_aditional   = $cspw_settings['tab_aditional'];
	$tab_reviews     = $cspw_settings['tab_reviews'];

	$cspw_settings_custom_product = get_option( 'cspw_settings_custom_product' );
	$custom_new_tab               = $cspw_settings_custom_product['custom_new_tab'];

	echo '<div style="display: inline-block;" class="cspw-product-custom-tabs">';

	if ( $tab_description != 1 ) {
		echo '<div class="cspw-product-custom-descripton">';
		echo '<h3>Descripción</h3>';
		echo '<p>' . $product->description . '</p>';
		echo '</div>';
	}
	if ( $tab_aditional != 1 ) {
		echo '<div class="cspw-product-custom-aditional-information">';
		echo '<h3>Información adicional</h3>';
		do_action( 'woocommerce_product_additional_information', $product );
		echo '</div>';
	}
	if ( $tab_reviews != 1 ) {
		?>
		<div id="comment-<?php comment_ID(); ?>" class="comment_container">

			<div id="reviews" class="woocommerce-Reviews">
			<div id="comments">
				<h2 class="woocommerce-Reviews-title">
					<?php
					$count = $product->get_review_count();
					if ( $count && wc_review_ratings_enabled() ) {
						/* translators: 1: reviews count 2: product name */
						$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
						echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
					} else {
						echo '<h3>Valoraciones</h3>';
					}
					?>
				</h2>

				<?php if ( have_comments() ) : ?>
					<ol class="commentlist">
						<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
					</ol>

					<?php
					if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
						echo '<nav class="woocommerce-pagination">';
						paginate_comments_links(
							apply_filters(
								'woocommerce_comment_pagination_args',
								array(
									'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
									'next_text' => is_rtl() ? '&larr;' : '&rarr;',
									'type'      => 'list',
								)
							)
						);
						echo '</nav>';
					endif;
					?>
				<?php else : ?>
					<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
				<div id="review_form_wrapper">
					<div id="review_form">
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							/* translators: %s is product title */
							'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
							/* translators: %s is product title */
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
							'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'   => '</span>',
							'comment_notes_after' => '',
							'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
							'logged_in_as'        => '',
							'comment_field'       => '',
						);

						$name_email_required = (bool) get_option( 'require_name_email', 1 );
						$fields              = array(
							'author' => array(
								'label'    => __( 'Name', 'woocommerce' ),
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_email_required,
							),
							'email'  => array(
								'label'    => __( 'Email', 'woocommerce' ),
								'type'     => 'email',
								'value'    => $commenter['comment_author_email'],
								'required' => $name_email_required,
							),
						);

						$comment_form['fields'] = array();

						foreach ( $fields as $key => $field ) {
							$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
							$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

							if ( $field['required'] ) {
								$field_html .= '&nbsp;<span class="required">*</span>';
							}

							$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

							$comment_form['fields'][ $key ] = $field_html;
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
							</select></div>';
						}

						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>
			<?php else : ?>
				<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
			<?php endif; ?>

			<div class="clear"></div>
		</div>
		<?php
	}
	if ( strlen($custom_new_tab) > 0 ) {
		echo '<div class="cspw-product-custom-new-tab">';
		echo '<h3>' . $custom_new_tab . '</h3>';
		echo cspw_new_product_tab_content();
		echo '</div>';
	}

	echo '</div>';

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
