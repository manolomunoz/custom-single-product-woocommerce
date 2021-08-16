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
	private $cspw_settings_custom_product;
	private $cspw_settings_custom_products;
	private $cspw_settings_init;

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
			'Custom Woocommerce',
			'Custom Woocommerce',
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
		$this->cspw_settings                 = get_option( 'cspw_settings' ); 
		$this->cspw_settings_custom_product  = get_option( 'cspw_settings_custom_product' );
		$this->cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
		$this->cspw_settings_init            = get_option( 'cspw_settings_init' );
		?>

		<div class="wrap">
			<h2><?php echo esc_html__( 'Configuración de la apariencia de productos', 'cspw_woocommerce' ) ?></h2>
			<p></p>
			<?php 
			settings_errors();
			if ( isset( $_GET ) ) {
				$active_tab = $_GET['tab'];
			} 
			?>
		    
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo $_GET['page']; ?>" class="nav-tab <?php echo $active_tab == null ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Configuraciones generales', 'cspw_woocommerce' ) ?></a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Producto individual', 'cspw_woocommerce' ) ?></a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=product_custom" class="nav-tab <?php echo $active_tab == 'product_custom' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Personalizar producto individual', 'cspw_woocommerce' ) ?></a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=products_settings" class="nav-tab <?php echo $active_tab == 'products_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Productos', 'cspw_woocommerce' ) ?></a>
			</h2>

			<?php	if ( null === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings_init' );
					do_settings_sections( 'cspw-general-settings-init' );
					submit_button();
					?>
				</form>
			<?php } ?>
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
					settings_fields( 'cspw_settings_custom_product' );
					do_settings_sections( 'cspw-settings-product-custom' );
					submit_button();
					?>
				</form>
			<?php } ?>
			<?php	if ( 'products_settings' === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings_custom_products' );
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

		// Custom product Settings.
		register_setting(
			'cspw_settings_custom_product',
			'cspw_settings_custom_product',
			array( $this, 'sanitize_fields' )
		);
		
		// Custom product Settings.
		register_setting(
			'cspw_settings_custom_products',
			'cspw_settings_custom_products',
			array( $this, 'sanitize_fields' )
		);

		// Custom product Settings.
		register_setting(
			'cspw_settings_init',
			'cspw_settings_init',
			array( $this, 'sanitize_fields' )
		);

		// ***************************************************** INIT CONFIGURATIONS *****************************************************
		add_settings_section(
			'cspw_setting_init_section',
			__( 'Configuraciones básicas', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_init' ),
			'cspw-general-settings-init'
		);

		// ***************************************************** PRODUCTO INDIVIDUAL *****************************************************
		add_settings_section(
			'cspw_setting_section',
			__( 'Configuración de la apariencia del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_info' ),
			'cspw-general-settings'
		);
		add_settings_field(
			'title',
			__( 'No mostrar el nombre del producto', 'cspw_woocommerce' ),
			array( $this, 'title_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'sku',
			__( 'No mostrar SKU del producto', 'cspw_woocommerce' ),
			array( $this, 'sku_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'categories',
			__( 'No mostrar categorías del producto', 'cspw_woocommerce' ),
			array( $this, 'categories_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'excerpt',
			__( 'No mostrar descripción del producto', 'cspw_woocommerce' ),
			array( $this, 'excerpt_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'price',
			__( 'No mostrar precio del producto', 'cspw_woocommerce' ),
			array( $this, 'price_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'image',
			__( 'No mostrar las imágenes del producto', 'cspw_woocommerce' ),
			array( $this, 'image_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'add_cart_button',
			__( 'No mostrar botón de añadir al carrito del producto', 'cspw_woocommerce' ),
			array( $this, 'add_cart_button_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);
		add_settings_field(
			'related_product',
			__( 'No mostrar los productos relacionados', 'cspw_woocommerce' ),
			array( $this, 'related_product_callback' ),
			'cspw-general-settings',
			'cspw_setting_section'
		);

		add_settings_section(
			'cspw_setting_section_tabs',
			__( 'Elige las tablas que deseas que no se muestren', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_tabs' ),
			'cspw-general-settings'
		);
		
		add_settings_field(
			'tab_description',
			__( 'Descripción', 'cspw_woocommerce' ),
			array( $this, 'tab_description_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);
		add_settings_field(
			'tab_aditional',
			__( 'Información adicional', 'cspw_woocommerce' ),
			array( $this, 'tab_aditional_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);
		add_settings_field(
			'tab_reviews',
			__( 'Valoraciones', 'cspw_woocommerce' ),
			array( $this, 'tab_reviews_callback' ),
			'cspw-general-settings',
			'cspw_setting_section_tabs'
		);

		// ***************************************************** PRODUCTOS *****************************************************
		add_settings_section(
			'cspw_setting_section_products',
			__( 'Configuración de la apariencia de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_info_products' ),
			'cspw-settings-products'
		);
		add_settings_field(
			'results',
			__( 'No mostrar el número de productos', 'cspw_woocommerce' ),
			array( $this, 'results_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'order',
			__( 'No mostrar desplegable de orden de los productos', 'cspw_woocommerce' ),
			array( $this, 'order_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_title',
			__( 'No mostrar el título de productos', 'cspw_woocommerce' ),
			array( $this, 'loop_title_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_image',
			__( 'No mostrar la imagen de los productos', 'cspw_woocommerce' ),
			array( $this, 'loop_image_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_price',
			__( 'No mostrar el precio de los productos', 'cspw_woocommerce' ),
			array( $this, 'loop_price_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'loop_add_cart_button',
			__( 'No mostrar botón añadir al carrito de los productos', 'cspw_woocommerce' ),
			array( $this, 'loop_add_cart_button_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);

		/********** ADD CART BUTTON **********/
		add_settings_section(
			'cspw_setting_section_products_button',
			__( 'Configuración del botón de añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_info_products_button' ),
			'cspw-settings-products'
		);
		add_settings_field(
			'custom_products_logo_add_cart_button',
			__( 'Añadir logo al botón añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'custom_products_logo_add_cart_button_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'custom_products_button_text',
			__( 'Cambiar texto del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_products_button_text_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'custom_products_button_before',
			__( 'Añadir texto antes del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_products_button_before_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'custom_products_button_after',
			__( 'Añadir texto después del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_products_button_after_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);

		// ***************************************************** PRODUCTO INDIVIDUAL POSITIONS *****************************************************
		add_settings_section(
			'cspw_setting_section_product_custom_button',
			__( 'Configuración del botón de añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_positions' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_logo_add_cart_button',
			__( 'Añadir logo al botón añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'custom_logo_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_add_cart_button',
			__( 'Cambiar texto del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_text_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_before_add_cart_button',
			__( 'Añadir texto antes del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_text_before_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'custom_text_after_add_cart_button',
			__( 'Añadir texto después del botón', 'cspw_woocommerce' ),
			array( $this, 'custom_text_after_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		
		/******************* PRICE ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_price',
			__( 'Configuración del precio', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_price' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_price_before',
			__( 'Añadir texto antes del precio', 'cspw_woocommerce' ),
			array( $this, 'custom_price_before_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);
		add_settings_field(
			'custom_price_after',
			__( 'Añadir texto después del precio', 'cspw_woocommerce' ),
			array( $this, 'custom_price_after_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);

		/******************* IMAGE ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_image',
			__( 'Configuración de la imagen', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_image' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_image_zoom',
			__( 'Quitar zoom de la imagen del producto', 'cspw_woocommerce' ),
			array( $this, 'custom_image_zoom_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_image'
		);

		/******************* TABS ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_tabs',
			__( 'Configuración de la imagen', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_tabs' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'custom_show_tabs',
			__( 'Modo de visualización de las tablas', 'cspw_woocommerce' ),
			array( $this, 'custom_show_tabs_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);

	}

	/**
	 * Sanitize fields before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields( $input ) {
		$sanitary_values = array();

		$settings_keys = array(
			// INIT CONFIGURATIONS
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
			'custom_products_logo_add_cart_button',
			'custom_products_button_text',
			'custom_products_button_before',
			'custom_products_button_after',
			// PRODUCTO INDIVIDUAL POSICIÓN
			'custom_logo_add_cart_button',
			'custom_text_add_cart_button',
			'custom_text_before_add_cart_button',
			'custom_text_after_add_cart_button',
			'custom_price_before',
			'custom_price_after',
			'custom_image_zoom',
			'custom_show_tabs',

		);

		foreach ( $settings_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$sanitary_values[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $sanitary_values;
	}


	/****************** INIT CONFIGURATIONS ******************/

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_init() {
		esc_html_e( 'Configuraciones generales para una mejor venta de productos.', 'cspw_woocommerce' );
	}


	/****************** PRODUCTO INDIVIDUAL ******************/

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_info() {
		esc_html_e( 'Configura la apariencia de los productos de tu tienda online.', 'cspw_woocommerce' );
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
		esc_html_e( 'Configura la apariencia de las tablas.', 'cspw_woocommerce' );
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
		esc_html_e( 'Configura la apariencia de los productos de tu tienda online.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for results
	 *
	 * @return void
	 */
	public function results_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_results" name="cspw_settings_custom_products[results]" value="1"' . checked( 1, $settings['results'], false ) . '/>';
	}

	/**
	 * Call back for order
	 *
	 * @return void
	 */
	public function order_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_order" name="cspw_settings_custom_products[order]" value="1"' . checked( 1, $settings['order'], false ) . '/>';
	}
	
	/**
	 * Call back for loop_title
	 *
	 * @return void
	 */
	public function loop_title_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_title" name="cspw_settings_custom_products[loop_title]" value="1"' . checked( 1, $settings['loop_title'], false ) . '/>';
	}

	/**
	 * Call back for loop_price
	 *
	 * @return void
	 */
	public function loop_price_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_price" name="cspw_settings_custom_products[loop_price]" value="1"' . checked( 1, $settings['loop_price'], false ) . '/>';
	}

	/**
	 * Call back for loop_image
	 *
	 * @return void
	 */
	public function loop_image_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_image" name="cspw_settings_custom_products[loop_image]" value="1"' . checked( 1, $settings['loop_image'], false ) . '/>';
	}

	/**
	 * Call back for loop_add_cart_button
	 *
	 * @return void
	 */
	public function loop_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_add_cart_button" name="cspw_settings_custom_products[loop_add_cart_button]" value="1"' . checked( 1, $settings['loop_add_cart_button'], false ) . '/>';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_info_products_button() {
		esc_html_e( 'Personaliza la apariencia del botón.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for custom_products_logo_add_cart_button
	 *
	 * @return void
	 */
	public function custom_products_logo_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		?>
		<label for="upload_image">
			<input id="custom_products_logo_add_cart_button" type="text" size="36" name="cspw_settings_custom_products[custom_products_logo_add_cart_button]" value="<?php echo esc_attr__( $settings['custom_products_logo_add_cart_button'], 'cspw_woocommerce' );  ?>" />
			<input id="upload_image_button_products" class="button" type="button" value="Seleccionar imagen" />
			<br /><?php esc_html_e( 'Ingresa una URL o añade una imagen', 'cspw_woocommerce' ) ?>
		</label>
		<?php
		wp_enqueue_media();
		wp_register_script( 'my-admin-js', esc_url( plugins_url( 'admin/js/upload_file.js', dirname( __FILE__ ) ) ), array( 'jquery' ) );
		wp_enqueue_script( 'my-admin-js' );
	}

	/**
	 * Call back for custom_products_button_text
	 *
	 * @return void
	 */
	public function custom_products_button_text_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="custom_products_button_text" name="cspw_settings_custom_products[custom_products_button_text]" size="40" type="text" value="' . esc_attr__( $settings['custom_products_button_text'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for custom_products_button_before
	 *
	 * @return void
	 */
	public function custom_products_button_before_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="custom_products_button_before" name="cspw_settings_custom_products[custom_products_button_before]" size="40" type="text" value="' . esc_attr__( $settings['custom_products_button_before'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for custom_products_button_after
	 *
	 * @return void
	 */
	public function custom_products_button_after_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="custom_products_button_after" name="cspw_settings_custom_products[custom_products_button_after]" size="40" type="text" value="' . esc_attr__( $settings['custom_products_button_after'], 'cspw_woocommerce' ) . '" />';
	}

	/****************** PRODUCTO INDIVIDUAL PERSONALIZACIÓN ******************/
	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_positions() {
		esc_html_e( 'Configura la posición de los elementos en la página de producto.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for custom_logo_add_cart_button
	 *
	 * @return void
	 */
	public function custom_logo_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		?>
		<label for="upload_image">
			<input id="custom_logo_add_cart_button" type="text" size="36" name="cspw_settings_custom_product[custom_logo_add_cart_button]" value="<?php echo esc_attr__( $settings['custom_logo_add_cart_button'], 'cspw_woocommerce' );  ?>" />
			<input id="upload_image_button" class="button" type="button" value="Seleccionar imagen" />
			<br /><?php esc_html_e( 'Ingresa una URL o añade una imagen', 'cspw_woocommerce' ) ?>
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
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="custom_text_add_cart_button" name="cspw_settings_custom_product[custom_text_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['custom_text_add_cart_button'], 'cspw_woocommerce' ) . '" />';
	}
	
	/**
	 * Call back for custom_text_before_add_cart_button
	 *
	 * @return void
	 */
	public function custom_text_before_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="custom_text_before_add_cart_button" name="cspw_settings_custom_product[custom_text_before_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['custom_text_before_add_cart_button'], 'cspw_woocommerce' ) . '" />';
	}
	
	/**
	 * Call back for custom_text_after_add_cart_button
	 *
	 * @return void
	 */
	public function custom_text_after_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="custom_text_after_add_cart_button" name="cspw_settings_custom_product[custom_text_after_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['custom_text_after_add_cart_button'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_price() {
		esc_html_e( 'Configuración del precio.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for custom_price_before
	 *
	 * @return void
	 */
	public function custom_price_before_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="custom_price_before" name="cspw_settings_custom_product[custom_price_before]" size="40" type="text" value="' . esc_attr__( $settings['custom_price_before'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for custom_price_after
	 *
	 * @return void
	 */
	public function custom_price_after_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="custom_price_after" name="cspw_settings_custom_product[custom_price_after]" size="40" type="text" value="' . esc_attr__( $settings['custom_price_after'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_image() {
		esc_html_e( 'Configuración de la imagen.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for custom_image_zoom
	 *
	 * @return void
	 */
	public function custom_image_zoom_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_custom_image_zoom" name="cspw_settings_custom_product[custom_image_zoom]" value="1"' . checked( 1, $settings['custom_image_zoom'], false ) . '/>';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_tabs() {
		esc_html_e( 'Configuración de la visualización de las tablas.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for custom_show_tabs
	 *
	 * @return void
	 */
	public function custom_show_tabs_callback() {
		$settings  = get_option( 'cspw_settings_custom_product' );
		$show_tabs = $settings['custom_show_tabs'];

		echo '<select id="cspw_custom_show_tabs" name="cspw_settings_custom_product[custom_show_tabs]">';

		echo '<option value="pestanas" ' . selected( $show_tabs, "pestanas" ) . '>' . __( 'Pestañas', 'cspw_woocommerce' ) . '</option>';
		echo '<option value="lista" ' . selected( $show_tabs, "lista" ) . '>' . __( 'Lista', 'cspw_woocommerce' ) . '</option>';

		echo '</select>';
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
			#cspw_custom_show_tabs {
				width: 200px;
			}
			
			';
		echo '</style>';
	}

}
if ( is_admin() ) {
	$cspw = new CSPW_Settings();
}