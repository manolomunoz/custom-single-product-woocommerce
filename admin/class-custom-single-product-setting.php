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
			esc_url( plugins_url( 'admin/icono.svg', dirname( __FILE__ ) ) ),
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
			<h2>Ajustes de la vista producto</h2>
			<p></p>
			<?php settings_errors(); ?>

			<?php $active_tab = isset( $_GET['tab'] ) ? strval( $_GET['tab'] ) : 'settings'; ?>

			<h2 class="nav-tab-wrapper">
				<a href="" class="nav-tab <?php echo 'settings' === $active_tab ? 'nav-tab-active' : ''; ?>">Ajustes</a>
			</h2>
			<?php	if ( 'settings' === $active_tab ) { ?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'cspw_settings' );
					do_settings_sections( 'sync-ecommerce-course-admin' );
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

		add_settings_section(
			'cspw_setting_section',
			__( 'Configuración de la apariencia del producto', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_info' ),
			'sync-ecommerce-course-admin'
		);
		add_settings_field(
			'sku',
			__( 'No mostrar SKU del producto', 'sync-ecommerce-course' ),
			array( $this, 'sku_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'categories',
			__( 'No mostrar categorías del producto', 'sync-ecommerce-course' ),
			array( $this, 'categories_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'excerpt',
			__( 'No mostrar descripción del producto', 'sync-ecommerce-course' ),
			array( $this, 'excerpt_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'tabs',
			__( 'Marca las tablas que deseas que no se muestren', 'sync-ecommerce-course' ),
			array( $this, 'cspw_section_tabs' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'tab_description',
			__( 'Descripción', 'sync-ecommerce-course' ),
			array( $this, 'tab_description_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'tab_aditional',
			__( 'Información adicional', 'sync-ecommerce-course' ),
			array( $this, 'tab_aditional_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
		);
		add_settings_field(
			'tab_reviews',
			__( 'Valoraciones', 'sync-ecommerce-course' ),
			array( $this, 'tab_reviews_callback' ),
			'sync-ecommerce-course-admin',
			'cspw_setting_section'
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
			'sku',
			'categories',
			'excerpt',
			'tab_description',
			'tab_aditional',
			'tab_reviews',

		);

		foreach ( $settings_keys as $key ) {
			if ( isset( $input[ $key ] ) ) {
				$sanitary_values[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $sanitary_values;
	}

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
	 * Custom CSS for admin
	 *
	 * @return void
	 */
	public function custom_css() {
		// Free Version.
		echo '
			<style>
			
			';
		echo '</style>';
	}

}
if ( is_admin() ) {
	$cspw = new CSPW_Settings();
}