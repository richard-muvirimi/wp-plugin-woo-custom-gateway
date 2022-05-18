<?php

namespace Rich4rdMuvirimi\WooCustomGateway\Views;

if ( ! defined( 'WPINC' ) ) {
	die(); // Exit if accessed directly.
}

/**
 * Provide payment proof field
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://tyganeutronics.com
 * @since 1.0.0
 */
?>

<fieldset>
	<p class="form-row form-row-wide">
		<label
			for="<?php esc_attr_e( WOO_CUSTOM_GATEWAY_SLUG ); ?>-note"><?php if (empty($description)) : _e( 'Payment Details.', WOO_CUSTOM_GATEWAY_SLUG ); endif; ?></label>

		<textarea id="<?php esc_attr_e( WOO_CUSTOM_GATEWAY_SLUG ); ?>-note" class="input-text"
			name="<?php esc_attr_e( WOO_CUSTOM_GATEWAY_SLUG ); ?>-note"></textarea>

	</p>
	<div class="clear"></div>
</fieldset>
