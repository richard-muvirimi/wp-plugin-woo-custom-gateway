<?php

/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area.
 * This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Custom Gateway
 * Plugin URI:        https://tyganeutronics.com/woo-custom-gateway/
 * Description:       Add multiple custom payment gateways to WooCommerce e-commerce plugin.
 * Version:           1.3.0
 * Author:            Tyganeutronics
 * Author URI:        https://tyganeutronics.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-custom-gateway
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to:   6.5.1
 *
 * @package WooCustomGateway
 *
 * @link https://tyganeutronics.com
 * @since 1.0.0
 */

use Rich4rdMuvirimi\WooCustomGateway\WooCustomGateway;

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die();
}

 /**
  * The plugin slug, one source of truth for context
  */
  define( 'WOO_CUSTOM_GATEWAY_SLUG', 'woo-custom-gateway' );

  /**
   * Plugin version number
   */
  define( 'WOO_CUSTOM_GATEWAY_VERSION', '1.3.0' );

  /**
   * Reference to this file, and this file only, (well, plugin entry point)
   */
   define( 'WOO_CUSTOM_GATEWAY_FILE', __FILE__ );

  /**
   * Plugin name as known to WordPress
   */
  define( 'WOO_CUSTOM_GATEWAY_NAME', plugin_basename( WOO_CUSTOM_GATEWAY_FILE ) );

/**
 * Load composer
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * And away we go
 */
 WooCustomGateway::instance()->run();
