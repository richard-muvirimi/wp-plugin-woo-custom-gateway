<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Fired during plugin activation
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 */

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
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
class Woo_Custom_Gateway_Activator
{

    /**
     * Short Description.
     * (use period)
     * Long Description.
     *
     * @since 1.0.0
     */
    public static function activate()
    {

        // insert sample posts
        $args = array('post_type' => 'woocg-post', 'fields' => 'ids', 'no_found_rows' => true);

        if (empty(get_posts($args))) {

            $id = wp_insert_post(array(
                'post_status' => 'publish',
                'post_type' => 'woocg-post',
                'post_title' => __('Sample Custom Gateway', WOO_CUSTOM_GATEWAY_SLUG),
                'meta_input' => array(
                    'woocg-desciption' => __('Sample payment gateway to just show off. ;)', WOO_CUSTOM_GATEWAY_SLUG) // ignore typo
                )
            ));
        }

        if (boolval(get_transient("woo-custom-gateway-rate")) === false) {
            set_transient("woo-custom-gateway-rate", true, defined("MONTH_IN_SECONDS") ? MONTH_IN_SECONDS * 3 : YEAR_IN_SECONDS / 4);
        }
    }
}