<?php

namespace RichardMuvirimi\WooCustomGateway\Views;

use RichardMuvirimi\WooCustomGateway\Model\Gateway;

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Provide an editor for the gateway options
 *
 * @package WooCustomGateway
 * @subpackage WooCustomGateway/Views
 *
 * @link https://richard.co.zw
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.4.0
 * @version 1.4.0
 *
 * @var string $field_key
 * @var array $data
 * @var Gateway $gateway
 * @var string $key
 */
?>

<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?><?php echo $gateway->get_tooltip_html($data); // WPCS: XSS ok.
            ?></label>
    </th>
    <td class="forminp">
        <fieldset>
            <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
            <?php wp_editor($gateway->get_option($key), esc_attr($field_key), array(
                "editor_class" => "input-text wide-input" . $data["class"],
                "editor_css" => "<style>" . $data["css"] . "</style>",
                "textarea_rows" => 3
            )) ?>
            <?php echo $gateway->get_description_html($data); // WPCS: XSS ok.
            ?>
        </fieldset>
    </td>
</tr>
