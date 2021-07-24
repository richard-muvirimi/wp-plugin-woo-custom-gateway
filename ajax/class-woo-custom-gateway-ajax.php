<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/admin
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/admin
 *
 * @author https://tyganeutronics.com <tygalive@gmail.com>
 */
class Woo_Custom_Gateway_Ajax
{

    /**
     * The ID of this plugin.
     *
     * @access private
     * @var string $plugin_name The ID of this plugin.
     * @since 1.0.0
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @access private
     * @var string $version The current version of this plugin.
     * @since 1.0.0
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     *            The name of this plugin.
     *            The version of this plugin.
     *
     * @since 1.0.0
     *
     * @param string $plugin_name
     * @param string $version
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Set reminder for half a year and send redirect link
     * 
     * @since 1.0.2
     *
     * @return void
     */
    public function ajaxDoRate()
    {
        if (check_ajax_referer("wcg-rate", "_ajax_nonce", false) !== false) {

            //remind again in three months
            set_transient($this->plugin_name . "-rate", true, defined("MONTH_IN_SECONDS") ? MONTH_IN_SECONDS * 6 : YEAR_IN_SECONDS / 2);

            echo wp_send_json(array(
                "redirect" => "https://wordpress.org/support/plugin/" . $this->plugin_name . "/reviews/"
            ), 200);
        }
    }

    /**
     * Remind again in a week
     * 
     * @since 1.0.2
     *
     * @return void
     */
    public function ajaxDoRemind()
    {

        if (check_ajax_referer("wcg-remind", "_ajax_nonce", false) !== false) {

            //remind after a week
            set_transient($this->plugin_name . "-rate", true, WEEK_IN_SECONDS);

            echo wp_send_json(array(
                "success" => true
            ), 200);
        }
    }

    /**
     * Remind in a year
     * 
     * @since 1.0.2
     *
     * @return void
     */
    public function ajaxDoCancel()
    {
        if (check_ajax_referer("wcg-cancel", "_ajax_nonce", false) !== false) {

            set_transient($this->plugin_name . "-rate", true, YEAR_IN_SECONDS);

            echo wp_send_json(array(
                "success" => true
            ), 200);
        }
    }
}