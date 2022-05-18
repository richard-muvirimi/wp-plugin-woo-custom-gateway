<?php

namespace Rich4rdMuvirimi\WooCustomGateway\Views;

if ( ! defined( 'WPINC' ) ) {
	die(); // Exit if accessed directly.
}

/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://tyganeutronics.com
 * @since 1.2.3
 * @version 1.2.3
 */

wp_nonce_field( WOO_CUSTOM_GATEWAY_SLUG, WOO_CUSTOM_GATEWAY_SLUG . '-nonce', false );

$description = get_post_meta( $post->ID, 'woocg-desciption', true ); // ignore typo

?>

<div class="<?php esc_attr_e( WOO_CUSTOM_GATEWAY_SLUG ); ?>">
	<div style="margin: 10px 0;">
		<label for="woocg-description"><?php esc_html_e( 'Gateway Description', WOO_CUSTOM_GATEWAY_SLUG ); ?></label>
	</div>
	<textarea rows="5" style="width: 100%;" name="woocg-description"
		placeholder="<?php esc_html_e( 'Gateway description', WOO_CUSTOM_GATEWAY_SLUG ); ?>"
		id="woocg-description"><?php esc_html_e( $description ); ?></textarea>
	<small><?php _e( 'Description for the payment method shown on the admin page.', WOO_CUSTOM_GATEWAY_SLUG ); ?></small>
</div>
