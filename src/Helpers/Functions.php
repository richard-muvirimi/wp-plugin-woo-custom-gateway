<?php

/**
 * Plugin helper functions
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Helpers
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Helpers;

use Rich4rdMuvirimi\WooCustomGateway\Model\Gateway;

/**
 * Class to handle plugin translations
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Helpers
 *
 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class Functions {

	/**
	 * Get initialized payment gateway class
	 *
	 * @return Rich4rdMuvirimi\WooCustomGateway\Model\Gateway|false
	 */
	public static function gateway_instance( $gateway ) {
		if ( function_exists( 'WC' ) ) {

			if ( WC()->payment_gateways  ) {
				$gateways = WC()->payment_gateways->payment_gateways();

				if ( isset( $gateways[ $gateway ] ) ) {

					$gateway = $gateways[ $gateway ];

					if ( $gateway instanceof Gateway){
						return $gateway;
					}
				}
			}
		}
		return false;
	}

	/**
	 * The slug name for payment gateway post types
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function gateway_slug() {
		return 'woocg-post';
	}

	/**
	 * Get unique plugin slug
	 *
	 * @param string $suffix
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.3.0
	 * @version 1.3.0
	 *
	 * @return string
	 */
	public static function get_plugin_slug( $suffix = '' ) {
		return WOO_CUSTOM_GATEWAY_SLUG . $suffix;
	}

	/**
	 * Get other templates (e.g. product attributes) passing attributes and including the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 */
	public static function get_template( $template_name, $args = array(), $template_path = '' ) {

		$template_path = self::get_views_path( $template_path );

		$template_path = apply_filters( WOO_CUSTOM_GATEWAY_SLUG . '-template', $template_path, $template_name, $args );

		extract( $args );

		ob_start();
		if ( $template_path ) {
			include $template_path;
		}
		return ob_get_clean();
	}

	/**
	 * Get the views path
	 *
	 * @param string $path
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_views_path( $path ) {
		return plugin_dir_path( WOO_CUSTOM_GATEWAY_FILE ) . 'src' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . ltrim( $path, '\\/' );
	}

	/**
	 * Get the scripts path
	 *
	 * @param string $path
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_script_path( $path ) {
		return self::get_views_path( 'js' . DIRECTORY_SEPARATOR . $path );
	}

	/**
	 * Get the styles path
	 *
	 * @param string $path
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_style_path( $path ) {
		return self::get_views_path( 'css' . DIRECTORY_SEPARATOR . $path );
	}

	/**
	 * Get the views url
	 *
	 * @param string $url
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_views_url( $url ) {
		return plugin_dir_url( WOO_CUSTOM_GATEWAY_FILE ) . 'src' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . ltrim( $url, '\\/' );
	}

	/**
	 * Get the scripts url
	 *
	 * @param string $url
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_script_url( $url ) {
		return self::get_views_url( 'js' . DIRECTORY_SEPARATOR . $url );
	}

	/**
	 * Get the styles url
	 *
	 * @param string $url
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	public static function get_style_url( $url ) {
		return self::get_views_url( 'css' . DIRECTORY_SEPARATOR . $url );
	}


	/**
	 * Get filtered gateway id
	 *
	 * @param int $id
	 *
	 * @author Richard Muvirimi <rich4rdmuvirimi@gmail.com>
	 * @since 1.3.0
	 * @version 1.3.0
	 *
	 * @return string
	 */
	public static function gateway_id( $id ) {

		/**
		 * Filter payment gateway id, has to be unique so that orders are not attributed to the wrong payment gateway
		 *
		 * @since 1.2.3
		 * @version 1.2.3
		 */
		return apply_filters( self::get_plugin_slug( '-gateway-id' ), self::gateway_slug() . '-' . $id, $id );
	}
}
