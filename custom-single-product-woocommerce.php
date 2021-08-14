<?php
/**
 * Plugin Name: Custom Single Product Woocommerce
 * Plugin URI:  
 * Description: Custom position and show element in single product page.
 * Version:     0.1
 * Author:      Manuel Muñoz Rodríguez
 * Author URI:  
 * Text Domain: woocommerce
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     Custom Single Product Woocommerce
 * @author      Manuel Muñoz Rodríguez
 * @copyright   2021
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      cspw
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once PLUGIN_PATH . '/admin/class-custom-single-product-setting.php';
require_once PLUGIN_PATH . '/public/class-custom-single-product-public.php';


add_action( 'plugins_loaded', 'cspw_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function cspw_plugin_init() {
	load_plugin_textdomain( 'woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
