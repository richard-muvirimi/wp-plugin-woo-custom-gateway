<?php

/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public
 *
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class Woo_Custom_Gateway_Public
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
     *            The name of the plugin.
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
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         * An instance of this class should be passed to the run() function
         * defined in Woo_Custom_Gateway_Loader as all of the hooks are defined
         * in that particular class.
         * The Woo_Custom_Gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-custom-gateway-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         * An instance of this class should be passed to the run() function
         * defined in Woo_Custom_Gateway_Loader as all of the hooks are defined
         * in that particular class.
         * The Woo_Custom_Gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-custom-gateway-public.js', array('jquery'), $this->version, false);
    }

    public function woo_add_gateways($gateways)
    {

        $args = array('post_type' => 'woocg-post', 'fields' => 'ids', 'no_found_rows' => true, 'post_status' => 'publish', 'numberposts' => -1);

        $posts = get_posts($args);

        foreach ($posts as $id) {

            $gateways[] = new WC_Woo_Custom_Gateway($id);
        }

        return $gateways;
    }

}