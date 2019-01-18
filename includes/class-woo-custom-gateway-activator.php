<?php

/**
 * Fired during plugin activation
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 */

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 1.0.0
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class Woo_Custom_Gateway_Activator {

	/**
	 * Short Description.
	 * (use period)
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		// insert sample posts
		$args = array( 'post_type' => 'woocg-post', 'fields' => 'ids', "no_found_rows" => true);

		if ( empty(get_posts($args)) ) {

			$id = wp_insert_post(array( 'post_status' => 'publish', 'post_type' => 'woocg-post', 'post_title' => __('Sample Custom Gateway', 'woo-custom-gateway'), 'meta_input' => array( 'woocg-desciption' => __("Sample payment gateway to just show off. ;)", "woo-custom-gateway"))));
		}

	}
}
