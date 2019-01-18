<?php

/**
 * Define the internationalization functionality
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 */

/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 1.0.0
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class Woo_Custom_Gateway_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain('plugin-name', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');

	}
}
