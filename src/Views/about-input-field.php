<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

/**
 * Display text field option
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://richard.co.zw
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.5.0
 * @version 1.5.0
 *
 * @var array $args
 */

?>

<!--suppress HtmlFormInputWithoutLabel -->
<input id="<?php esc_attr_e($args['label_for']) ?>"
       name="<?php esc_attr_e($args['label_for']) ?>"
       data-custom="<?php esc_attr_e($args['value']); ?>"
       type="<?php esc_attr_e($args['type']); ?>"
       placeholder="<?php esc_attr_e($args['placeholder'] ?? ""); ?>"
    <?php checked($args['value'], "on") ?> />

<div>
    <small class="description">
        <?php _e($args['description']); ?>
    </small>
</div>
