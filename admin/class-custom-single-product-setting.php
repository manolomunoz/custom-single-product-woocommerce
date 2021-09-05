<?php
/**
 * Custom single product setting
 *
 * Show configuration menu in administrator part
 *
 * @author   Manuel Muñoz Rodríguez
 * @category Functions
 * @package  Admin
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
		$this->cspw_settings_custom_product  = get_option( 'cspw_settings_custom_product' );
		$this->cspw_settings_custom_products = get_option( 'cspw_settings_custom_products' );
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
				<a href="?page=<?php echo $_GET['page']; ?>" class="nav-tab <?php echo $active_tab == null ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Personalizar producto individual', 'cspw_woocommerce' ) ?></a>
				<a href="?page=<?php echo $_GET['page']; ?>&tab=products_settings" class="nav-tab <?php echo $active_tab == 'products_settings' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Productos', 'cspw_woocommerce' ) ?></a>
			</h2>

			<?php	if ( null === $active_tab ) { ?>
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

		// ***************************************************** PRODUCTO INDIVIDUAL *****************************************************
		add_settings_section(
			'cspw_setting_section',
			__( 'Configuración de la apariencia del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_info' ),
			'cspw-general-settings'
		);
		

		// ***************************************************** CUSTOM SINGLE PRODUCT *****************************************************

		/******************* BASICS ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_basic',
			__( 'Configuración básica del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_basic' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'cspw_title',
			__( 'No mostrar el nombre del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_title_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_basic'
		);
		add_settings_field(
			'cspw_sku',
			__( 'No mostrar SKU del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_sku_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_basic'
		);
		add_settings_field(
			'cspw_categories',
			__( 'No mostrar categorías del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_categories_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_basic'
		);
		add_settings_field(
			'cspw_excerpt',
			__( 'No mostrar descripción del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_excerpt_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_basic'
		);

		/******************* ADD TO CART BUTTON ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_button',
			__( 'Configuración del producto individual', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_positions' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'cspw_add_cart_button',
			__( 'No mostrar botón de añadir al carrito del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'cspw_custom_logo_add_cart_button',
			__( 'Añadir logo al botón añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_logo_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'cspw_custom_text_add_cart_button',
			__( 'Cambiar texto del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_text_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'cspw_custom_text_before_add_cart_button',
			__( 'Añadir texto antes del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_text_before_add_cart_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_button'
		);
		add_settings_field(
			'cspw_custom_text_after_add_cart_button',
			__( 'Añadir texto después del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_text_after_add_cart_button_callback' ),
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
			'cspw_price',
			__( 'No mostrar precio del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_price_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);
		add_settings_field(
			'cspw_custom_price_before',
			__( 'Añadir texto antes del precio', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_price_before_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_price'
		);
		add_settings_field(
			'cspw_custom_price_after',
			__( 'Añadir texto después del precio', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_price_after_callback' ),
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
			'cspw_image',
			__( 'No mostrar las imágenes del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_image_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_image'
		);
		add_settings_field(
			'cspw_custom_image_zoom',
			__( 'Quitar zoom de la imagen del producto', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_image_zoom_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_image'
		);

		/******************* TABS ******************/
		add_settings_section(
			'cspw_setting_section_product_custom_tabs',
			__( 'Configuración de las tablas', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_tabs' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'cspw_tab_description',
			__( 'Descripción', 'cspw_woocommerce' ),
			array( $this, 'cspw_tab_description_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);
		add_settings_field(
			'cspw_tab_aditional',
			__( 'Información adicional', 'cspw_woocommerce' ),
			array( $this, 'cspw_tab_aditional_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);
		add_settings_field(
			'cspw_tab_reviews',
			__( 'Valoraciones', 'cspw_woocommerce' ),
			array( $this, 'cspw_tab_reviews_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);
		add_settings_field(
			'cspw_custom_show_tabs',
			__( 'Modo de visualización de las tablas', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_show_tabs_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);
		add_settings_field(
			'cspw_custom_new_tab',
			__( 'Añadir nueva tabla (nombre)', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_new_tab_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);
		add_settings_field(
			'cspw_custom_new_tab_content',
			__( 'Añadir contenido a la nueva tabla', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_new_tab_content_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_custom_tabs'
		);

		/******************* RELATED PRODUCTS ******************/
		add_settings_section(
			'cspw_setting_section_product_related_product',
			__( 'Configuración de los productos relacionados', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_related_product' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'cspw_related_product',
			__( 'No mostrar los productos relacionados', 'cspw_woocommerce' ),
			array( $this, 'cspw_related_product_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_related_product'
		);
		add_settings_field(
			'cspw_custom_show_related_product_button',
			__( 'Quitar "Añadir al carrito" de los productos relacionados', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_show_related_product_button_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_related_product'
		);

		/******************* COMMENTS ******************/
		add_settings_section(
			'cspw_setting_section_product_comments',
			__( 'Configuración de los comentarios', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_product_comments' ),
			'cspw-settings-product-custom'
		);
		add_settings_field(
			'cspw_custom_show_product_custom_comment',
			__( 'Modo de visualización de los comentarios', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_show_product_custom_comment_callback' ),
			'cspw-settings-product-custom',
			'cspw_setting_section_product_comments'
		);

		// ***************************************************** PRODUCTOS *****************************************************
		add_settings_section(
			'cspw_setting_section_products',
			__( 'Configuración de la apariencia de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_section_info_products' ),
			'cspw-settings-products'
		);
		add_settings_field(
			'cspw_results',
			__( 'No mostrar el número de productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_results_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'cspw_order',
			__( 'No mostrar desplegable de orden de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_order_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'cspw_loop_title',
			__( 'No mostrar el título de productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_loop_title_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'cspw_loop_image',
			__( 'No mostrar la imagen de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_loop_image_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'cspw_loop_price',
			__( 'No mostrar el precio de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_loop_price_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products'
		);
		add_settings_field(
			'cspw_loop_add_cart_button',
			__( 'No mostrar botón añadir al carrito de los productos', 'cspw_woocommerce' ),
			array( $this, 'cspw_loop_add_cart_button_callback' ),
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
			'cspw_custom_products_logo_add_cart_button',
			__( 'Añadir logo al botón añadir al carrito', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_products_logo_add_cart_button_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'cspw_custom_products_button_text',
			__( 'Cambiar texto del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_products_button_text_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'cspw_custom_products_button_before',
			__( 'Añadir texto antes del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_products_button_before_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
		);
		add_settings_field(
			'cspw_custom_products_button_after',
			__( 'Añadir texto después del botón', 'cspw_woocommerce' ),
			array( $this, 'cspw_custom_products_button_after_callback' ),
			'cspw-settings-products',
			'cspw_setting_section_products_button'
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
			'cspw_title',
			'cspw_sku',
			'cspw_categories',
			'cspw_excerpt',
			'cspw_price',
			'cspw_image',
			'cspw_add_cart_button',
			'cspw_tab_description',
			'cspw_tab_aditional',
			'cspw_tab_reviews',
			'cspw_related_product',
			'cspw_custom_logo_add_cart_button',
			'cspw_custom_text_add_cart_button',
			'cspw_custom_text_before_add_cart_button',
			'cspw_custom_text_after_add_cart_button',
			'cspw_custom_price_before',
			'cspw_custom_price_after',
			'cspw_custom_image_zoom',
			'cspw_custom_show_tabs',
			'cspw_custom_new_tab',
			'cspw_custom_new_tab_content',
			'cspw_custom_show_related_product_button',
			'cspw_custom_show_product_custom_comment',
			// PRODUCTOS
			'cspw_results',
			'cspw_order',
			'cspw_loop_title',
			'cspw_loop_price',
			'cspw_loop_image',
			'cspw_loop_add_cart_button',
			'cspw_custom_products_logo_add_cart_button',
			'cspw_custom_products_button_text',
			'cspw_custom_products_button_before',
			'cspw_custom_products_button_after',
		);

		foreach ( $settings_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				if ( $key == 'cspw_custom_new_tab_content' ) {
					$sanitary_values[ $key ] = wp_kses_post( $input[ $key ] );
				} else {
					$sanitary_values[ $key ] = sanitize_text_field( $input[ $key ] );
				}
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
		esc_html_e( 'Configura la apariencia de los productos de tu tienda online.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for cspw_sku
	 *
	 * @return void
	 */
	public function cspw_sku_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_sku" name="cspw_settings_custom_product[cspw_sku]" value="1"' . checked( 1, $settings['cspw_sku'], false ) . '/>';
	}

	/**
	 * Call back for cspw_title
	 *
	 * @return void
	 */
	public function cspw_title_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_title" name="cspw_settings_custom_product[cspw_title]" value="1"' . checked( 1, $settings['cspw_title'], false ) . '/>';
	}

	/**
	 * Call back for cspw_categories
	 *
	 * @return void
	 */
	public function cspw_categories_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_categories" name="cspw_settings_custom_product[cspw_categories]" value="1"' . checked( 1, $settings['cspw_categories'], false ) . '/>';
	}

	/**
	 * Call back for cspw_excerpt
	 *
	 * @return void
	 */
	public function cspw_excerpt_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_excerpt" name="cspw_settings_custom_product[cspw_excerpt]" value="1"' . checked( 1, $settings['cspw_excerpt'], false ) . '/>';
	}

	/**
	 * Call back for cspw_price
	 *
	 * @return void
	 */
	public function cspw_price_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_price" name="cspw_settings_custom_product[cspw_price]" value="1"' . checked( 1, $settings['cspw_price'], false ) . '/>';
	}

	/**
	 * Call back for cspw_image
	 *
	 * @return void
	 */
	public function cspw_image_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_image" name="cspw_settings_custom_product[cspw_image]" value="1"' . checked( 1, $settings['cspw_image'], false ) . '/>';
	}
	
	/**
	 * Call back for cspw_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_add_cart_button" name="cspw_settings_custom_product[cspw_add_cart_button]" value="1"' . checked( 1, $settings['cspw_add_cart_button'], false ) . '/>';
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
	 * Call back for cspw_tab_description
	 *
	 * @return void
	 */
	public function cspw_tab_description_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_tab_description" name="cspw_settings_custom_product[cspw_tab_description]" value="1"' . checked( 1, $settings['cspw_tab_description'], false ) . '/>';
	}

	/**
	 * Call back for cspw_tab_aditional
	 *
	 * @return void
	 */
	public function cspw_tab_aditional_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_tab_aditional" name="cspw_settings_custom_product[cspw_tab_aditional]" value="1"' . checked( 1, $settings['cspw_tab_aditional'], false ) . '/>';
	}

	/**
	 * Call back for cspw_tab_reviews
	 *
	 * @return void
	 */
	public function cspw_tab_reviews_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_tab_reviews" name="cspw_settings_custom_product[cspw_tab_reviews]" value="1"' . checked( 1, $settings['cspw_tab_reviews'], false ) . '/>';
	}

	/**
	 * Call back for tab cspw_related_product
	 *
	 * @return void
	 */
	public function cspw_related_product_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_related_product" name="cspw_settings_custom_product[cspw_related_product]" value="1"' . checked( 1, $settings['cspw_related_product'], false ) . '/>';
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
	 * Call back for cspw_results
	 *
	 * @return void
	 */
	public function cspw_results_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_results" name="cspw_settings_custom_products[cspw_results]" value="1"' . checked( 1, $settings['cspw_results'], false ) . '/>';
	}

	/**
	 * Call back for cspw_order
	 *
	 * @return void
	 */
	public function cspw_order_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_order" name="cspw_settings_custom_products[cspw_order]" value="1"' . checked( 1, $settings['cspw_order'], false ) . '/>';
	}
	
	/**
	 * Call back for cspw_loop_title
	 *
	 * @return void
	 */
	public function cspw_loop_title_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_title" name="cspw_settings_custom_products[cspw_loop_title]" value="1"' . checked( 1, $settings['cspw_loop_title'], false ) . '/>';
	}

	/**
	 * Call back for cspw_loop_price
	 *
	 * @return void
	 */
	public function cspw_loop_price_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_price" name="cspw_settings_custom_products[cspw_loop_price]" value="1"' . checked( 1, $settings['cspw_loop_price'], false ) . '/>';
	}

	/**
	 * Call back for cspw_loop_image
	 *
	 * @return void
	 */
	public function cspw_loop_image_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_image" name="cspw_settings_custom_products[cspw_loop_image]" value="1"' . checked( 1, $settings['cspw_loop_image'], false ) . '/>';
	}

	/**
	 * Call back for cspw_loop_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_loop_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input type="checkbox" id="cspw_loop_add_cart_button" name="cspw_settings_custom_products[cspw_loop_add_cart_button]" value="1"' . checked( 1, $settings['cspw_loop_add_cart_button'], false ) . '/>';
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
	 * Call back for cspw_custom_products_logo_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_custom_products_logo_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		?>
		<label for="upload_image">
			<input id="cspw_custom_products_logo_add_cart_button" type="text" size="36" name="cspw_settings_custom_products[cspw_custom_products_logo_add_cart_button]" value="<?php echo esc_attr__( $settings['cspw_custom_products_logo_add_cart_button'], 'cspw_woocommerce' );  ?>" />
			<input id="cspw_upload_image_button_products" class="button" type="button" value="Seleccionar imagen" />
			<br /><?php esc_html_e( 'Ingresa una URL o añade una imagen', 'cspw_woocommerce' ) ?>
		</label>
		<?php
		wp_enqueue_media();
		wp_register_script( 'my-admin-js', esc_url( plugins_url( 'admin/js/upload_file.js', dirname( __FILE__ ) ) ), array( 'jquery' ) );
		wp_enqueue_script( 'my-admin-js' );
	}

	/**
	 * Call back for cspw_custom_products_button_text
	 *
	 * @return void
	 */
	public function cspw_custom_products_button_text_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="cspw_custom_products_button_text" name="cspw_settings_custom_products[cspw_custom_products_button_text]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_products_button_text'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for cspw_custom_products_button_before
	 *
	 * @return void
	 */
	public function cspw_custom_products_button_before_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="cspw_custom_products_button_before" name="cspw_settings_custom_products[cspw_custom_products_button_before]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_products_button_before'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for cspw_custom_products_button_after
	 *
	 * @return void
	 */
	public function cspw_custom_products_button_after_callback() {
		$settings = get_option( 'cspw_settings_custom_products' );
		
		echo '<input id="cspw_custom_products_button_after" name="cspw_settings_custom_products[cspw_custom_products_button_after]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_products_button_after'], 'cspw_woocommerce' ) . '" />';
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
	 * Call back for cspw_custom_logo_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_custom_logo_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		?>
		<label for="upload_image">
			<input id="cspw_custom_logo_add_cart_button" type="text" size="36" name="cspw_settings_custom_product[cspw_custom_logo_add_cart_button]" value="<?php echo esc_attr__( $settings['cspw_custom_logo_add_cart_button'], 'cspw_woocommerce' );  ?>" />
			<input id="cspw_upload_image_button" class="button" type="button" value="Seleccionar imagen" />
			<br /><?php esc_html_e( 'Ingresa una URL o añade una imagen', 'cspw_woocommerce' ) ?>
		</label>
		<?php
		wp_enqueue_media();
		wp_register_script( 'my-admin-js', esc_url( plugins_url( 'admin/js/upload_file.js', dirname( __FILE__ ) ) ), array( 'jquery' ) );
		wp_enqueue_script( 'my-admin-js' );
	}

	/**
	 * Call back for cspw_custom_text_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_custom_text_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="cspw_custom_text_add_cart_button" name="cspw_settings_custom_product[cspw_custom_text_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_text_add_cart_button'], 'cspw_woocommerce' ) . '" />';
	}
	
	/**
	 * Call back for cspw_custom_text_before_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_custom_text_before_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="cspw_custom_text_before_add_cart_button" name="cspw_settings_custom_product[cspw_custom_text_before_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_text_before_add_cart_button'], 'cspw_woocommerce' ) . '" />';
	}
	
	/**
	 * Call back for cspw_custom_text_after_add_cart_button
	 *
	 * @return void
	 */
	public function cspw_custom_text_after_add_cart_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="cspw_custom_text_after_add_cart_button" name="cspw_settings_custom_product[cspw_custom_text_after_add_cart_button]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_text_after_add_cart_button'], 'cspw_woocommerce' ) . '" />';
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
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_basic() {
		esc_html_e( 'Configuraciones principales.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for cspw_custom_price_before
	 *
	 * @return void
	 */
	public function cspw_custom_price_before_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="cspw_custom_price_before" name="cspw_settings_custom_product[cspw_custom_price_before]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_price_before'], 'cspw_woocommerce' ) . '" />';
	}

	/**
	 * Call back for cspw_custom_price_after
	 *
	 * @return void
	 */
	public function cspw_custom_price_after_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input id="cspw_custom_price_after" name="cspw_settings_custom_product[cspw_custom_price_after]" size="40" type="text" value="' . esc_attr__( $settings['cspw_custom_price_after'], 'cspw_woocommerce' ) . '" />';
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
	 * Call back for cspw_custom_image_zoom
	 *
	 * @return void
	 */
	public function cspw_custom_image_zoom_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_cspw_custom_image_zoom" name="cspw_settings_custom_product[cspw_custom_image_zoom]" value="1"' . checked( 1, $settings['cspw_custom_image_zoom'], false ) . '/>';
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
	 * Call back for cspw_custom_show_tabs
	 *
	 * @return void
	 */
	public function cspw_custom_show_tabs_callback() {
		$settings  = get_option( 'cspw_settings_custom_product' );
		$show_tabs = $settings['cspw_custom_show_tabs'];

		echo '<select id="cspw_custom_show_tabs" name="cspw_settings_custom_product[cspw_custom_show_tabs]">';

		echo '<option value="pestanas" ' . selected( $show_tabs, "pestanas" ) . '>' . __( 'Pestañas', 'cspw_woocommerce' ) . '</option>';
		echo '<option value="lista" ' . selected( $show_tabs, "lista" ) . '>' . __( 'Lista', 'cspw_woocommerce' ) . '</option>';

		echo '</select>';
	}
	
	/**
	 * Call back for cspw_custom_new_tab
	 *
	 * @return void
	 */
	public function cspw_custom_new_tab_callback( $args ) {
		$settings  = get_option( 'cspw_settings_custom_product' );

		echo '<input type="text" id="cspw_custom_new_tab" name="cspw_settings_custom_product[cspw_custom_new_tab]" value="' . esc_attr__( $settings['cspw_custom_new_tab'], 'cspw_woocommerce' ) . '" />';
		
	}

	/**
	 * Call back for cspw_custom_new_tab_content
	 *
	 * @return void
	 */
	public function cspw_custom_new_tab_content_callback( $args ) {
		$settings  = get_option( 'cspw_settings_custom_product' );

		$content = isset( $settings['cspw_custom_new_tab_content'] ) ?  $settings['cspw_custom_new_tab_content'] : false;
		$args = array(
			'textarea_name' => 'cspw_settings_custom_product[cspw_custom_new_tab_content]',
			'wpautop'       => true,
			'tinymce'       => array(
			    'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
			),
		);
		wp_editor( $content, 'cspw_custom_new_tab_content', $args );
		
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_related_product() {
		esc_html_e( 'Configura la vista de los productos relacionados.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for cspw_custom_show_related_product_button
	 *
	 * @return void
	 */
	public function cspw_custom_show_related_product_button_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="checkbox" id="cspw_custom_show_related_product_button" name="cspw_settings_custom_product[cspw_custom_show_related_product_button]" value="1"' . checked( 1, $settings['cspw_custom_show_related_product_button'], false ) . '/>';
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function cspw_section_product_comments() {
		esc_html_e( 'Configura la vista de los comentarios para una mejor visión de usuario.', 'cspw_woocommerce' );
	}

	/**
	 * Call back for cspw_custom_show_product_custom_comment
	 *
	 * @return void
	 */
	public function cspw_custom_show_product_custom_comment_callback() {
		$settings = get_option( 'cspw_settings_custom_product' );
		
		echo '<input type="radio" name="cspw_settings_custom_product[cspw_custom_show_product_custom_comment]" value="1" ' . checked( 1, $settings['cspw_custom_show_product_custom_comment'], false ) . ' />Por defecto';
		echo '<br>';
		echo '<input type="radio" name="cspw_settings_custom_product[cspw_custom_show_product_custom_comment]" value="2" ' . checked( 2, $settings['cspw_custom_show_product_custom_comment'], false ) . ' />Nombre abreviado, apellido largo. Ejemplo: R. Nadal';
		echo '<br>';
		echo '<input type="radio" name="cspw_settings_custom_product[cspw_custom_show_product_custom_comment]" value="3" ' . checked( 3, $settings['cspw_custom_show_product_custom_comment'], false ) . ' />Nombre completo, apellido abreviado. Ejemplo: Rafael N.';
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
			#cspw_custom_logo_add_cart_button {
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