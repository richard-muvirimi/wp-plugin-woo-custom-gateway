<?php
/**
 * Bootstrap the plugin
 *
 * This file is the entry point into the plugin, registering all functions
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.2
 */

namespace Rich4rdMuvirimi\WooCustomGateway;

use BadMethodCallException;
use Rich4rdMuvirimi\WooCustomGateway\Controller\Admin;
use Rich4rdMuvirimi\WooCustomGateway\Controller\Ajax;
use Rich4rdMuvirimi\WooCustomGateway\Controller\Plugin;
use Rich4rdMuvirimi\WooCustomGateway\Locale\I18n;
use Rich4rdMuvirimi\WooCustomGateway\Controller\Site;
use Rich4rdMuvirimi\WooCustomGateway\Helpers\Functions;

/**
 * Class to bootstrap the plugin
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.2
 *
 * @method void register_deactivation_hook($file,$component, $method)
 *  {@see \register_deactivation_hook}
 * @method void register_uninstall_hook($file,$component, $method)
 *  {@see \register_uninstall_hook}
 * @method void register_activation_hook($file,$component, $method)
 *  {@see \register_activation_hook}
 * @method bool|true add_filter($hook_name, $component, $method, $priority = 10, $accepted_args = 1)
 *  {@see \add_filter}
 * @method bool|true add_action($hook_name, $component, $method, $priority = 10, $accepted_args = 1)
 *  {@see \add_action}
 */
class WooCustomGateway {

	/**
	 * Hold reference to a single instance of this class
	 *
	 * @var self
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	private static $instance;

	/**
	 * Bootstrap the plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return self
	 */
	public static function instance() {

		if ( ! ( self::$instance instanceof WooCustomGateway ) ) {
			self::$instance = new WooCustomGateway();
		}
		return self::$instance;
	}

	/**
	 * Init plugin Loader
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	protected function __construct() {
		$this->register_activation_hook( WOO_CUSTOM_GATEWAY_FILE, Plugin::class, 'on_activation' );
		$this->register_deactivation_hook( WOO_CUSTOM_GATEWAY_FILE, Plugin::class, 'on_deactivation' );
		$this->register_uninstall_hook( WOO_CUSTOM_GATEWAY_FILE, Plugin::class, 'on_uninstall' );
	}

	/**
	 * Define hooks for the admin side of the plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 * @return void
	 */
	public function define_admin_hooks() {
		$controller = new Admin();

		// add our custom gateways to woocommerce
		$this->add_filter( 'woocommerce_payment_gateways', $controller, 'payment_gateways' );

		$this->add_action( 'admin_enqueue_scripts', $controller, 'enqueue_styles' );
		$this->add_action( 'admin_enqueue_scripts', $controller, 'enqueue_scripts' );

		// Add custom action links
		$this->add_filter( 'plugin_action_links_' . WOO_CUSTOM_GATEWAY_NAME, $controller, 'plugins_list_options_link' );

		$this->add_filter( 'init', $controller, 'init' );

		// change featured image text
		$this->add_filter( 'admin_post_thumbnail_html', $controller, 'filter_featured_image_admin_text', 10, 3 );

		$this->add_filter( 'save_post_' . Functions::gateway_slug(), $controller, 'save_post', 10, 3 );

		// admin gateways list
		$this->add_filter( 'manage_' . Functions::gateway_slug() . '_posts_columns', $controller, 'add_columns' );
		$this->add_filter( 'post_row_actions', $controller, 'post_row_actions', 10, 2 );
		$this->add_action( 'manage_' . Functions::gateway_slug() . '_posts_custom_column', $controller, 'add_column_data', 10, 2 );

		$this->add_filter( 'enter_title_here', $controller, 'custom_enter_title', 10, 2 );

		// on post delete
		$this->add_action( 'before_delete_post', $controller, 'on_delete_method' );

		// request rating
		$this->add_filter( 'admin_notices', $controller, 'show_rating' );
	}

	/**
	 * Register hooks for the site side of the plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function define_site_hooks() {
		$controller = new Site();

		// Customer Emails
		$this->add_action( 'woocommerce_email_before_order_table', $controller, 'gateway_email_instructions', 10, 3 );

		$this->add_action( 'woocommerce_thankyou', $controller, 'woocommerce_thankyou' );

	}

	/**
	 * Register hooks for the ajax side of the plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function define_ajax_hooks() {
		$controller = new Ajax();

		$this->add_action( 'wp_ajax_' . WOO_CUSTOM_GATEWAY_SLUG . '-rate', $controller, 'ajaxDoRate' );
		$this->add_action( 'wp_ajax_' . WOO_CUSTOM_GATEWAY_SLUG . '-remind', $controller, 'ajaxDoRemind' );
		$this->add_action( 'wp_ajax_' . WOO_CUSTOM_GATEWAY_SLUG . '-cancel', $controller, 'ajaxDoCancel' );

	}

	/**
	 * Set the plugin locale
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function define_locale() {
		$locale = new I18n();

		$this->add_action( 'plugins_loaded', $locale, 'load_plugin_textdomain' );
	}

	/**
	 * Initialise the plugin
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function run() {
		$this->define_locale();
		$this->define_admin_hooks();
		$this->define_site_hooks();
		$this->define_ajax_hooks();
	}

	/**
	 * Call the appropriate WordPress registration hooks
	 *
	 * Allows us to hook the functions to this class so that we have a unified api
	 *
	 * @since 1.0.0
	 * @version 1.0.2
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed #type intentionally left out.
	 * @throws BadMethodCallException When called function does not exist or has missing arguments.
	 */
	public function __call( $name, $arguments ) {

		assert( count( $arguments ) >= 2, new BadMethodCallException( 'You need to provide at least two arguments for ' . $name ) );

		switch ( $name ) {
			case 'register_activation_hook':
			case 'register_deactivation_hook':
			case 'register_uninstall_hook':
				// Hook file
				$file = array_shift( $arguments );

				assert( file_exists( $file ), new BadMethodCallException( 'Please provide a valid file path for ' . $name ) );

				// Function to call
				$component = array_shift( $arguments );
				if ( is_array( $component ) || is_string( $component ) ) {
					$callable = $component;
				} else {
					$callable = array( $component, array_shift( $arguments ) );
				}
				unset( $component );

				assert( is_callable( $callable, true ), new BadMethodCallException( 'Please provide a callable function for ' . $name ) );

				// Register Hook
				$name( $file, $callable );
				break;
			case 'add_filter':
			case 'add_action':
				// The hook
				$hook = array_shift( $arguments );

				assert( is_string( $hook ), new BadMethodCallException( 'Please provide the name of the hook for ' . $name ) );

				// Function to call
				$component = array_shift( $arguments );
				if ( is_array( $component ) || is_string( $component ) ) {
					$callable = $component;
				} else {
					$callable = array( $component, array_shift( $arguments ) );
				}
				unset( $component );

				assert( is_callable( $callable, true ), new BadMethodCallException( 'Please provide a callable function for ' . $name ) );

				// Function Priority
				$priority = array_shift( $arguments );
				if ( is_null( $priority ) ) {
					$priority = 10;
				}

				assert( is_numeric( $priority ), new BadMethodCallException( 'Priority should be numeric for ' . $name ) );

				// Arguments Count
				$args = array_shift( $arguments );
				if ( is_null( $args ) ) {
					$args = 1;
				}

				assert( is_numeric( $args ), new BadMethodCallException( 'Number of arguments should be numeric for ' . $name ) );

				// Register hook
				return $name( $hook, $callable, $priority, $args );

			default:
				throw new BadMethodCallException( 'The method ' . $name . ' does not exist' );
		}
	}
}
