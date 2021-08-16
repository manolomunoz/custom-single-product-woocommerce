<?php
/**
 * custom-single-product-setting
 *
 * Show configuration menu in administrator part
 *
 * @author   Manuel Muñoz Rodríguez
 * @category admin
 * @package  admin
 * @version  0.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings
 */
class CSPW_Settings {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private $cspw_settings;

	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_head', array( $this, 'custom_css' ) );
	}

	/**
	 * Adds plugin page.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_menu_page(
			'Custom single product',
			'Custom single product',
			'manage_options',
			'cspw',
			array( $this, 'create_admin_page' ),
			esc_url( plugins_url( 'admin/images/icono.svg', dirname( __FILE__ ) ) ),
			99
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->cspw_settings = get_option( 'cspw_settings' ); ?>

		<div class="wrap">
			<h2>Configuración de la apariencia de productos</h2>
			<p></p>
			<?php 
			settings_errors();
			if ( isset( $_GET ) ) {
				$active_tab = $_GET['tab'];
			} 
			?>
		    
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo $_GET['page']; ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Producto individual</a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=product_custom" class="nav-tab <?php echo $active_tab == 'product_custom' ? 'nav-tab-active' : ''; ?>">Personalizar producto individual</a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=products_settings" class="nav-tab <?php echo $active_tab == 'products_settings' ? 'nav-tab-active' : ''; ?>">Productos</a>
			</h2>

			<?php	if ( 'settings' === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings' );
					do_settings_sections( 'cspw-general-settings' );
					submit_button();
					?>
				</form>
			<?php } ?>
			<?php	if ( 'product_custom' === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings' );
					do_settings_sections( 'cspw-settings-product-custom' );
					submit_button();
					?>
				</form>
			<?php } ?>
			<?php	if ( 'products_settings' === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings' );
					do_settings_sections( 'cspw-settings-products' );
					submit_button();
					?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Init for page
	 *
	 * @return void
	 */
	public function page_init() {
		// General Settings.
		register_setting(
			'cspw_settings',
			'cspw_settings',
			array( $this, 'sanitize_fields' )
		);

		// ***************************************************** PRODUCTO INDIVIDUAL *****************************************************
		add_settings_section(
			'cspw_setting_section',
			__( 'Configuración de la apariencia del producto', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_info' ),
			'cspw-general-settings'
		);
		add_settings_field(
			'title',
			__( 'No mostrar el nombre del producto', 'sync-ecommerce-course' ),
			array( $this, 'title_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'sku',
			__( 'No mostrar SKU del producto', 'sync-ecommerce-course' ),
			array( $this, 'sku_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'categories',
			__( 'No mostrar categorías del producto', 'sync-ecommerce-course' ),
			array( $this, 'categories_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'excerpt',
			__( 'No mostrar descripción del producto', 'sync-ecommerce-course' ),
			array( $this, 'excerpt_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'price',
			__( 'No mostrar precio del producto', 'sync-ecommerce-course' ),
			array( $this, 'price_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'image',
			__( 'No mostrar las imágenes del producto', 'sync-ecommerce-course' ),
			array( $this, 'image_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'add_cart_button',
			__( 'No mostrar botón de añadir al carrito del producto', 'sync-ecommerce-course' ),
			array( $this, 'add_cart_button_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'related_product',
			__( 'No mostrar los productos relacionados', 'sync-ecommerce-course' ),
			array( $this, 'related_product_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);

		add_settings_section(
			'cspw_setting_section_tabs',
			__( 'Elige las tablas que deseas que no se muestren', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_tabs' ),
			'cspw-general-settings'
		);
		
		add_settings_field(
			'tab_description',
			__( 'Descripción', 'sync-ecommerce-course' ),
			array( $this, 'tab_description_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);
		add_settings_field(
			'tab_aditional',
			__( 'Información adicional', 'sync-ecommerce-course' ),
			array( $this, 'tab_aditional_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);
		add_settings_field(
			'tab_reviews',
			__( 'Valoraciones', 'sync-ecommerce-course' ),
			array( $this, 'tab_reviews_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);

		// ***************************************************** PRODUCTOS *****************************************************
		add_settings_section(
			'cspw_setting_section_products',
			__( 'Configuración de la apariencia de los productos', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_info_products' ),
			'cspw-settings-products'
		);
		add_settings_field(
			'results',
			__( 'No mostrar el número de productos', 'sync-ecommerce-course' ),
			array( $this, 'results_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'order',
			__( 'No mostrar desplegable de orden de los productos', 'sync-ecommerce-course' ),
			array( $this, 'order_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_title',
			__( 'No mostrar el título de productos', 'sync-ecommerce-course' ),
			array( $this, 'loop_title_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_image',
			__( 'No mostrar la imagen de los productos', 'sync-ecommerce-course' ),
			array( $this, 'loop_image_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_price',
			__( 'No mostrar el precio de los productos', 'sync-ecommerce-course' ),
			array( $this, 'loop_price_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_add_cart_button',
			__( 'No mostrar botón añadir al carrito de los productos', 'sync-ecommerce-course' ),
			array( $this, 'loop_add_cart_button_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);

		// ***************************************************** PRODUCTO INDIVIDUAL POSITIONS *****************************************************
		add_settings_section(
			'cspw_setting_section_product_custom_button',
			__( 'Configuración del botón de añadir al carrito', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_product_positions' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_logo_add_cart_button',
			__( 'Añadir logo al botón añadir al carrito', 'sync-ecommerce-course' ),
			array( $this, 'custom_logo_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_add_cart_button',
			__( 'Cambiar texto del botón', 'sync-ecommerce-course' ),
			array( $this, 'custom_text_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_before_add_cart_button',
			__( 'Añadir texto antes del botón', 'sync-ecommerce-course' ),
			array( $this, 'custom_text_before_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_after_add_cart_button',
			__( 'Añadir texto después del botón', 'sync-ecommerce-course' ),
			array( $this, 'custom_text_after_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		
		/******************* PRICE ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_price',
			__( 'Configuración del precio', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_product_price' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_price_before',
			__( 'Añadir texto antes del precio', 'sync-ecommerce-course' ),
			array( $this, 'custom_price_before_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);
		add_settings_field(
			'custom_price_after',
			__( 'Añadir texto después del precio', 'sync-ecommerce-course' ),
			array( $this, 'custom_price_after_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);

		/******************* IMAGE ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_image',
			__( 'Configuración de la imagen', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_product_image' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_image_zoom',
			__( 'Quitar zoom de la imagen del producto', 'sync-ecommerce-course' ),
			array( $this, 'custom_image_zoom_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_image'
		);

	}

	/**
	 * Sanitize fiels before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields( $input ) {
		$sanitary_values = array();

		$settings_keys = array(
			// PRODUCTO INDIVIDUAL
			'title',
			'sku',
			'categories',
			'excerpt',
			'price',
			'image',
			'add_cart_button',
			'tab_description',
			'tab_aditional',
			'tab_reviews',
			'related_product',
			// PRODUCTOS
			'results',
			'order',
			'loop_title',
			'loop_price',
			'loop_image',
			'loop_add_cart_button',
			// PRODUCTO INDIVIDUAL POSICIÓN
			'custom_logo_add_cart_button',
			'custom_text_add_cart_button',
			'custom_text_before_add_cart_button',
			'custom_text_after_add_cart_button',
			'custom_price_before',
			'custom_price_after',
			'custom_image_zoom',

		);

		foreach ( $settings_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$sanitary_values[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $sanitary_values;
	}

	/****************** PRODUCTO INDIVIDUAL ******************/

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_info() {
		esc_html_e( 'Configura la apariencia de los productos de tu tienda online.', 'sync-ecommerce-course' );
	}

	/**
	 * Call back for sku
	 *
	 * @return void
	 */
	public function sku_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_sku" name="cspw_settings[sku]" value="1"' . checked( 1, $settings['sku'], false ) . '/>';
	}

	/**
	 * Call back for title
	 *
	 * @return void
	 */
	public function title_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_title" name="cspw_settings[title]" value="1"' . checked( 1, $settings['title'], false ) . '/>';
	}

	/**
	 * Call back for categories
	 *
	 * @return void
	 */
	public function categories_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_categories" name="cspw_settings[categories]" value="1"' . checked( 1, $settings['categories'], false ) . '/>';
	}

	/**
	 * Call back for excerpt
	 *
	 * @return void
	 */
	public function excerpt_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_excerpt" name="cspw_settings[excerpt]" value="1"' . checked( 1, $settings['excerpt'], false ) . '/>';
	}

	/**
	 * Call back for price
	 *
	 * @return void
	 */
	public function price_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_price" name="cspw_settings[price]" value="1"' . checked( 1, $settings['price'], false ) . '/>';
	}

	/**
	 * Call back for image
	 *
	 * @return void
	 */
	public function image_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_image" name="cspw_settings[image]" value="1"' . checked( 1, $settings['image'], false ) . '/>';
	}
	
	/**
	 * Call back for add_cart_button
	 *
	 * @return void
	 */
	public function add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_add_cart_button" name="cspw_settings[add_cart_button]" value="1"' . checked( 1, $settings['add_cart_button'], false ) . '/>';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_tabs() {
		echo '<p class="cpsw-subtitulo">Configura la apariencia de las tablas.</p>';
	}

	/**
	 * Call back for tab description
	 *
	 * @return void
	 */
	public function tab_description_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_tab_description" name="cspw_settings[tab_description]" value="1"' . checked( 1, $settings['tab_description'], false ) . '/>';
	}

	/**
	 * Call back for tab aditional
	 *
	 * @return void
	 */
	public function tab_aditional_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_tab_aditional" name="cspw_settings[tab_aditional]" value="1"' . checked( 1, $settings['tab_aditional'], false ) . '/>';
	}

	/**
	 * Call back for tab reviews
	 *
	 * @return void
	 */
	public function tab_reviews_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_tab_reviews" name="cspw_settings[tab_reviews]" value="1"' . checked( 1, $settings['tab_reviews'], false ) . '/>';
	}

	/**
	 * Call back for tab related_product
	 *
	 * @return void
	 */
	public function related_product_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_related_product" name="cspw_settings[related_product]" value="1"' . checked( 1, $settings['related_product'], false ) . '/>';
	}

	/****************** PRODUCTOS ******************/

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_info_products() {
		esc_html_e( 'Configura la apariencia de los productos de tu tienda online.', 'sync-ecommerce-course' );
	}

	/**
	 * Call back for results
	 *
	 * @return void
	 */
	public function results_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_results" name="cspw_settings[results]" value="1"' . checked( 1, $settings['results'], false ) . '/>';
	}

	/**
	 * Call back for order
	 *
	 * @return void
	 */
	public function order_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_order" name="cspw_settings[order]" value="1"' . checked( 1, $settings['order'], false ) . '/>';
	}
	
	/**
	 * Call back for loop_title
	 *
	 * @return void
	 */
	public function loop_title_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_loop_title" name="cspw_settings[loop_title]" value="1"' . checked( 1, $settings['loop_title'], false ) . '/>';
	}

	/**
	 * Call back for loop_price
	 *
	 * @return void
	 */
	public function loop_price_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_loop_price" name="cspw_settings[loop_price]" value="1"' . checked( 1, $settings['loop_price'], false ) . '/>';
	}

	/**
	 * Call back for loop_image
	 *
	 * @return void
	 */
	public function loop_image_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_loop_image" name="cspw_settings[loop_image]" value="1"' . checked( 1, $settings['loop_image'], false ) . '/>';
	}

	/**
	 * Call back for loop_add_cart_button
	 *
	 * @return void
	 */
	public function loop_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_loop_add_cart_button" name="cspw_settings[loop_add_cart_button]" value="1"' . checked( 1, $settings['loop_add_cart_button'], false ) . '/>';
	}

	/****************** PRODUCTO INDIVIDUAL PERSONALIZACIÓN ******************/
	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_positions() {
		esc_html_e( 'Configura la posición de los elementos en la página de producto.', 'sync-ecommerce-course' );
	}

	/**
	 * Call back for custom_logo_add_cart_button
	 *
	 * @return void
	 */
	public function custom_logo_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		?>
		<label for="upload_image">
			<input id="custom_logo_add_cart_button" type="text" size="36" name="cspw_settings[custom_logo_add_cart_button]" value="<?php echo $settings['custom_logo_add_cart_button'] ?>" />
			<input id="upload_image_button" class="button" type="button" value="Seleccionar imagen" />
			<br />Ingresa una URL o añade una imagen
		</label>
		<?php
		wp_enqueue_media();
		wp_register_script( 'my-admin-js', esc_url( plugins_url( 'admin/js/upload_file.js', dirname( __FILE__ ) ) ), array( 'jquery' ) );
		wp_enqueue_script( 'my-admin-js' );
		
		
	}

	/**
	 * Call back for custom_text_add_cart_button
	 *
	 * @return void
	 */
	public function custom_text_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input id="custom_text_add_cart_button" name="cspw_settings[custom_text_add_cart_button]" size="40" type="text" value="' . $settings['custom_text_add_cart_button'] . '" />';
	}
	
	/**
	 * Call back for custom_text_before_add_cart_button
	 *
	 * @return void
	 */
	public function custom_text_before_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input id="custom_text_before_add_cart_button" name="cspw_settings[custom_text_before_add_cart_button]" size="40" type="text" value="' . $settings['custom_text_before_add_cart_button'] . '" />';
	}
	
	/**
	 * Call back for custom_text_after_add_cart_button
	 *
	 * @return void
	 */
	public function custom_text_after_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input id="custom_text_after_add_cart_button" name="cspw_settings[custom_text_after_add_cart_button]" size="40" type="text" value="' . $settings['custom_text_after_add_cart_button'] . '" />';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_price() {
		esc_html_e( 'Configuración del precio.', 'sync-ecommerce-course' );
	}

	/**
	 * Call back for custom_price_before
	 *
	 * @return void
	 */
	public function custom_price_before_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input id="custom_price_before" name="cspw_settings[custom_price_before]" size="40" type="text" value="' . $settings['custom_price_before'] . '" />';
	}

	/**
	 * Call back for custom_price_after
	 *
	 * @return void
	 */
	public function custom_price_after_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input id="custom_price_after" name="cspw_settings[custom_price_after]" size="40" type="text" value="' . $settings['custom_price_after'] . '" />';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_image() {
		esc_html_e( 'Configuración de la imagen.', 'sync-ecommerce-course' );
	}

	/**
	 * Call back for custom_image_zoom
	 *
	 * @return void
	 */
	public function custom_image_zoom_callback() {
		$settings = get_option( 'cspw_settings' );
		
		echo '<input type="checkbox" id="cspw_custom_image_zoom" name="cspw_settings[custom_image_zoom]" value="1"' . checked( 1, $settings['custom_image_zoom'], false ) . '/>';
	}

	/**
	 * Custom CSS for admin
	 *
	 * @return void
	 */
	public function custom_css() {
		// Free Version.
		echo '
			<style>
			#custom_logo_add_cart_button {
				width: 350px;
			}
			';
		echo '</style>';
	}

}
if ( is_admin() ) {
	$cspw = new CSPW_Settings();
}