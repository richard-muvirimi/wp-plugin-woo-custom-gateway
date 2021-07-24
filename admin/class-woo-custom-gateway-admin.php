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
class Woo_Custom_Gateway_Admin
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
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_styles()
    {

        wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-custom-gateway-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-custom-gateway-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, "wcg", array(
            "ajax_url" => admin_url('admin-ajax.php'),
            "name" => $this->plugin_name
        ));
    }

    /**
     * Add setings page link
     * 
     * @since 1.0.0
     * @param array $links
     * @return array
     */
    public function plugins_list_options_link($links)
    {

        $args = array('post_type' => 'woocg-post');

        $link = add_query_arg($args, admin_url('edit.php'));

        $plugin_links = array('<a href="' . $link . '">' . __('Payment Methods', $this->plugin_name) . '</a>');

        // Merge our new link with the default ones

        return array_merge($plugin_links, $links);
    }

    /**
     * Register custom post typre
     *
     * @since 1.0.0
     * @return void
     */
    public function init()
    {

        register_post_type('woocg-post', array(
            'show_ui' => true,
            'show_in_menu' => current_user_can('manage_woocommerce') ? 'woocommerce' : true,
            'description' => 'woocg-post',
            // 'has_archive' => true,
            'exclude_from_search' => true,
            // 'map_meta_cap' => true,
            'hierarchical' => false,
            'labels' => array(
                'name' => __('Payment Methods', $this->plugin_name),
                'singular_name' => __('Payment Method', $this->plugin_name),
                'add_new' => __('Add New', $this->plugin_name),
                'add_new_item' => __('Add New Payment Method', $this->plugin_name),
                'edit_item' => __('Edit Payment Method', $this->plugin_name),
                'new_item' => __('New Payment Method', $this->plugin_name),
                'view_item' => __('View Payment Method', $this->plugin_name),
                'search_items' => __('Search Payment Methods', $this->plugin_name),
                'not_found' => __('No Payment Methods Found', $this->plugin_name),
                'not_found_in_trash' => __('No Payment Methods found in trash', $this->plugin_name),
                'parent_item_colon' => __('Parent Payment Method:', $this->plugin_name),
                'all_items' => __('Payment Methods', $this->plugin_name),
                'archives' => __('Payment Method archives', $this->plugin_name),
                'insert_into_item' => __('Insert into Payment Method profile', $this->plugin_name),
                'uploaded_to_this_item' => __('Uploaded to Payment Method profile', $this->plugin_name),
                'menu_name' => __('Payment Methods', $this->plugin_name),
                'name_admin_bar' => __('Payment Methods', $this->plugin_name)
            ),
            'rewrite' => array('slug' => 'custom-gateway', $this->plugin_name),
            'supports' => array('thumbnail', 'title', $this->plugin_name),
            'delete_with_user' => false,
            'register_meta_box_cb' => array($this, 'addMetaBoxs')
        ));
    }

    /**
     *
     * @since 1.0.0
     * @param int      $post_id
     * @param \WP_Post $post
     * @param boolean  $update
     */
    public function save_post($post_id, $post, $update)
    {

        $description = sanitize_text_field(filter_input(INPUT_POST, 'woocg_post_description_editor'));

        if ($description) {
            update_post_meta($post_id, 'woocg-desciption', $description);
        }
    }

    /**
     * Show rating request
     *
     * @since 1.1.0
     * @version 1.1.1
     * @return void
     */
    public function show_rating()
    {
        /**
         * Request Rating
         */
        //if has at least two custom gateways and has never been shown
        $args = array('post_type' => 'woocg-post', 'fields' => 'ids', 'no_found_rows' => true);

        if (boolval(get_transient($this->plugin_name . "-rate")) === false && count(get_posts($args)) >= 2) {
            wp_enqueue_script($this->plugin_name);
            wp_enqueue_style($this->plugin_name);

            include plugin_dir_path(__FILE__) . "partials/woo-custom-gateway-admin-rating.php";
        }
    }

    /**
     * 
     * @since 1.0.0
     * @param \WP_Post $post
     */
    public function addMetaBoxs($post)
    {

        add_meta_box('woocg-post-description', __('Payment Method Description', $this->plugin_name), array($this, 'descriptionMetaBox'), 'woocg-post', 'normal', 'high');
    }

    /**
     * 
     * @since 1.0.0
     * @param \WP_Post $post
     * @param array    $args
     */
    public function descriptionMetaBox($post, $args)
    {

        $description = get_post_meta($post->ID, 'woocg-desciption', true);

        $description = esc_html($description);

        $html = '<textarea rows="1" cols="40" name="woocg_post_description_editor" tabindex="6" id="excerpt">' . $description . '</textarea>';

        if (empty($description)) {
            $html .= wpautop(__('Description for the payment method shown on the admin page.', $this->plugin_name));
        }

        echo $html;
    }

    /**
     *
     * @since 1.0.0
     * @param  string   $content
     * @param  int      $post_id
     * @param  int      $thumbnail_id
     * @return string
     */
    public function filter_featured_image_admin_text($content, $post_id, $thumbnail_id)
    {

        if (get_post_field('post_type', $post_id) === 'woocg-post' && $thumbnail_id == null) {
            $content .= wpautop(__('If you want to show an image next to the gateway\'s name on the frontend, select an image.', $this->plugin_name));
        }

        return $content;
    }

    /**
     *
     * @since 1.0.0
     * @param  string   $input
     * @param  \WP_Post $post
     * @return string
     */
    public function custom_enter_title($input, $post)
    {

        if ('woocg-post' === $post->post_type) {
            $input = __('Payment Method Name', $this->plugin_name);
        }

        return $input;
    }

    /**
     * 
     * @since 1.0.0
     * @param $column
     * @param $post_id
     */
    public function add_column_data($column, $post_id)
    {

        switch ($column) {
            case 'thumbnail':
                echo the_post_thumbnail('thumb');
                break;
        }
    }

    /**
     * 
     * @since 1.0.0
     * @param  $columns
     * @return mixed
     */
    public function add_columns($columns)
    {

        $columns['thumbnail'] = __('Thumbnail', $this->plugin_name);

        return $columns;
    }

    /**
     *
     * @since 1.0.0
     * @param  array    $actions
     * @param  \WP_Post $post
     * @return string
     */
    public function post_row_actions($actions, $post)
    {

        if ($post->post_type === 'woocg-post') {

            $args = array('page' => 'wc-settings', 'tab' => 'checkout', 'section' => $post->post_type . '-' . $post->ID);

            $link = add_query_arg($args, admin_url('admin.php'));

            $actions['settings'] = '<a href="' . $link . '">' . __('Settings', $this->plugin_name) . '</a>';
        }

        return $actions;
    }

    /**
     * On delete custom post type
     *
     * @since 1.0.0
     * @param int $postid
     */
    public function on_delete_method($postid)
    {

        $method = new WC_Woo_Custom_Gateway($postid);

        delete_option($method->get_option_key());
    }

    /**
     * Get a url targetting self
     *
     * @param array $arguments
     * @since 1.1.1
     * @return void
     */
    function url_targetting_self($arguments)
    {
        $arguments = array_merge(
            array(
                "action" => $this->plugin_name,
                $this->plugin_name . "-nonce" => wp_create_nonce($this->plugin_name)
            ),
            filter_input_array(INPUT_GET) ?: array(),
            $arguments
        );

        return admin_url(get_current_screen()->base . ".php") . "?" . http_build_query($arguments);
    }
}