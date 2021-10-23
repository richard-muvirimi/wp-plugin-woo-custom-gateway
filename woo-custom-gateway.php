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
 * Version:           1.2.0
 * Author:            Tyganeutronics
 * Author URI:        https://tyganeutronics.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-custom-gateway
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to:   5.8.0
 *
 *
 * @package Woo_Custom_Gateway
 *
 * @link https://tyganeutronics.com
 * @since 1.0.0
 */

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die();
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOO_CUSTOM_GATEWAY_VERSION', '1.2.0');
define('WOO_CUSTOM_GATEWAY_SLUG', 'woo-custom-gateway');

/**
 * Reference to this file and this file only.
 */
define('WOO_CUSTOM_GATEWAY_NAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-custom-gateway-activator.php
 */
function activate_Woo_Custom_Gateway()
{

    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-custom-gateway-activator.php';
    Woo_Custom_Gateway_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-custom-gateway-deactivator.php
 */
function deactivate_Woo_Custom_Gateway()
{

    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-custom-gateway-deactivator.php';
    Woo_Custom_Gateway_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_Woo_Custom_Gateway');
register_deactivation_hook(__FILE__, 'deactivate_Woo_Custom_Gateway');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woo-custom-gateway.php';

/**
 * Begins execution of the plugin.
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_Woo_Custom_Gateway()
{

    $plugin = new Woo_Custom_Gateway_Main();
    $plugin->run();
}

// ... and off we go -------->>>
run_Woo_Custom_Gateway();