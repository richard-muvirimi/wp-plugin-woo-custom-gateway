<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 */

/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/includes
 *
 * @author tyganeutronics <tygalive@gmail.com>
 *
 * @since 1.0.0
 */
class Woo_Custom_Gateway_Main
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @access protected
     * @var Woo_Custom_Gateway_Loader $loader Maintains and registers all hooks for the plugin.
     * @since 1.0.0
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @access protected
     * @var string $plugin_name The string used to uniquely identify this plugin.
     * @since 1.0.0
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @access protected
     * @var string $version The current version of the plugin.
     * @since 1.0.0
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {

        if (defined('WOO_CUSTOM_GATEWAY_VERSION')) {
            $this->version = WOO_CUSTOM_GATEWAY_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'woo-custom-gateway';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     * Include the following files that make up the plugin:
     * - Woo_Custom_Gateway_Loader. Orchestrates the hooks of the plugin.
     * - Woo_Custom_Gateway_i18n. Defines internationalization functionality.
     * - Woo_Custom_Gateway_Admin. Defines all hooks for the admin area.
     * - Woo_Custom_Gateway_Public. Defines all hooks for the public side of the site.
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @access private
     * @since 1.0.0
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-custom-gateway-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-custom-gateway-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-custom-gateway-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woo-custom-gateway-public.php';

        $this->loader = new Woo_Custom_Gateway_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     * Uses the Woo_Custom_Gateway_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @access private
     * @since 1.0.0
     */
    private function set_locale()
    {

        $plugin_i18n = new Woo_Custom_Gateway_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access private
     * @since 1.0.0
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Woo_Custom_Gateway_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add custom action links
        $this->loader->add_filter('plugin_action_links_' . plugin_basename(WOO_CUSTOM_GATEWAY_FILE), $plugin_admin, 'plugins_list_options_link');

        $this->loader->add_filter('init', $plugin_admin, 'init');

        // lazy load custom gateway class
        $this->loader->add_action('plugins_loaded', $this, 'init_custom_payment_gateway');

        // change featured image text
        $this->loader->add_filter('admin_post_thumbnail_html', $plugin_admin, 'filter_featured_image_admin_text', 10, 3);

        $this->loader->add_filter('save_post_woocg-post', $plugin_admin, 'save_post', 10, 3);

        // admin gateways list
        $this->loader->add_filter('manage_woocg-post_posts_columns', $plugin_admin, 'add_columns');
        $this->loader->add_filter('post_row_actions', $plugin_admin, 'post_row_actions', 10, 2);
        $this->loader->add_action('manage_woocg-post_posts_custom_column', $plugin_admin, 'add_column_data', 10, 2);

        $this->loader->add_filter('enter_title_here', $plugin_admin, 'custom_enter_title', 10, 2);

        // on post delete
        $this->loader->add_action('before_delete_post', $plugin_admin, 'on_delete_method');

        // request rating
        $this->loader->add_filter('admin_notices', $plugin_admin, 'show_rating');

        $this->loader->add_filter('admin_action_' . $this->plugin_name, $plugin_admin, 'handle_action');
    }

    /**
     * Include files
     *
     * @since 1.0.0
     * @return void
     */
    public function init_custom_payment_gateway()
    {

        if (function_exists('WC')) {

            /**
             * The blueprint class of a custom gateway
             */
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/custom-gateway-blueprint.php';
        }
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access private
     * @since 1.0.0
     */
    private function define_public_hooks()
    {

        $plugin_public = new Woo_Custom_Gateway_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // add our custom gateways to woocommerce
        $this->loader->add_filter('woocommerce_payment_gateways', $plugin_public, 'woo_add_gateways');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {

        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     *
     * @since 1.0.0
     *
     * @return string The name of the plugin.
     */
    public function get_plugin_name()
    {

        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     *
     * @since 1.0.0
     *
     * @return Woo_Custom_Gateway_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {

        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     *
     * @since 1.0.0
     *
     * @return string The version number of the plugin.
     */
    public function get_version()
    {

        return $this->version;
    }
}