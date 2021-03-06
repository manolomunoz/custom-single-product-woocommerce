<?php
/**
 * Plugin Name: Custom Single Product WC
 * Plugin URI:  
 * Description: Customize woocommerce products and add custom content.
 * Version:     0.1
 * Author:      Manuel Muñoz Rodríguez
 * Author URI:  https://github.com/manolomunoz
 * Text Domain: woocommerce
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     WordPress
 * @author      Manuel Muñoz Rodríguez <mmr010496@gmail.com>
 * @copyright   2021
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      cspw
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'PLUGIN_CSPW_PATH', plugin_dir_path( __FILE__ ) );

require_once PLUGIN_CSPW_PATH . '/admin/class-custom-single-product-setting.php';
require_once PLUGIN_CSPW_PATH . '/public/custom-single-product-public.php';
require_once PLUGIN_CSPW_PATH . '/public/custom-products-loop-public.php';


add_action( 'plugins_loaded', 'cspw_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function cspw_plugin_init() {
	load_plugin_textdomain( 'custom-single-product-wc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
