<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Define the internationalization functionality
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 */

/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 *
 * @author Tyganeutronics <tygalive@gmail.com>
 *
 * @since 1.0.0
 */
class Woo_Custom_Gateway_i18n
{

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain('plugin-name', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
    }
}
