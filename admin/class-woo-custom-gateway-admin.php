<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://tyganeutronics.com/woo-custom-gateway/
 * @since 1.0.0
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/admin
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class Woo_Custom_Gateway_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name
	 *        	The name of this plugin.
	 * @param string $version
	 *        	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Custom_Gateway_Loader as all of the hooks are defined
		 * in that particular class.
		 * The Woo_Custom_Gateway_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-custom-gateway-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Custom_Gateway_Loader as all of the hooks are defined
		 * in that particular class.
		 * The Woo_Custom_Gateway_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-custom-gateway-admin.js', array( 'jquery'), $this->version, false);

	}

	public function plugins_list_options_link( $links ) {

		$args = array( "post_type" => "woocg-post");

		$link = add_query_arg($args, admin_url('edit.php'));

		$plugin_links = array( '<a href="' . $link . '">' . __('Payment Methods', 'woo-custom-gateway') . '</a>');

		// Merge our new link with the default ones
		return array_merge($plugin_links, $links);

	}

	public function init() {

		register_post_type('woocg-post', array( 'show_ui' => true,
												'show_in_menu' => current_user_can('manage_woocommerce') ? 'woocommerce' : true,
												'description' => 'woocg-post',
												// 'has_archive' => true,
												'exclude_from_search' => true,
												// 'map_meta_cap' => true,
												'hierarchical' => false,
												'labels' => array( 'name' => __('Payment Methods', 'woo-custom-gateway'),
																'singular_name' => __('Payment Method', 'woo-custom-gateway'),
																'add_new' => __('Add New', 'woo-custom-gateway'),
																'add_new_item' => __('Add New Payment Method', 'woo-custom-gateway'),
																'edit_item' => __('Edit Payment Method', 'woo-custom-gateway'),
																'new_item' => __('New Payment Method', 'woo-custom-gateway'),
																'view_item' => __('View Payment Method', 'woo-custom-gateway'),
																'search_items' => __('Search Payment Methods', 'woo-custom-gateway'),
																'not_found' => __('No Payment Methods Found', 'woo-custom-gateway'),
																'not_found_in_trash' => __('No Payment Methods found in trash', 'woo-custom-gateway'),
																'parent_item_colon' => __('Parent Payment Method:', 'woo-custom-gateway'),
																'all_items' => __('Payment Methods', 'woo-custom-gateway'),
																'archives' => __('Payment Method archives', 'woo-custom-gateway'),
																'insert_into_item' => __('Insert into Payment Method profile', 'woo-custom-gateway'),
																'uploaded_to_this_item' => __('Uploaded to Payment Method profile', 'woo-custom-gateway'),
																'menu_name' => __('Payment Methods', 'woo-custom-gateway'),
																'name_admin_bar' => __('Payment Methods', 'woo-custom-gateway')),
												'rewrite' => array( 'slug' => 'custom-gateway', 'woo-custom-gateway'),
												'supports' => array( 'thumbnail', 'title', 'woo-custom-gateway'),
												'delete_with_user' => false,
												'register_meta_box_cb' => array( $this, 'addMetaBoxs')));

	}

	/**
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 * @param boolean $update
	 */
	public function save_post( $post_id, $post, $update ) {

		$description = sanitize_text_field(filter_input(INPUT_POST, 'woocg_post_description_editor'));

		if ( $description ) {
			update_post_meta($post_id, 'woocg-desciption', $description);
		}

	}

	/**
	 *
	 * @param \WP_Post $post
	 *        	Post object
	 */
	function addMetaBoxs( $post ) {

		add_meta_box('woocg-post-description', __('Payment Method Description', 'woo-custom-gateway'), array( $this, 'descriptionMetaBox'), 'woocg-post', 'normal', 'high');

	}

	/**
	 *
	 * @param \WP_Post $post
	 *        	Post object
	 * @param array $args
	 */
	public function descriptionMetaBox( $post, $args ) {

		$description = get_post_meta($post->ID, 'woocg-desciption', true);

		$description = esc_html($description);

		$html = '<textarea rows="1" cols="40" name="woocg_post_description_editor" tabindex="6" id="excerpt">' . $description . '</textarea>';
		if ( empty($description) ) {
			$html .= wpautop(__('Description for the payment method shown on the admin page.', 'woo-custom-gateway'));
		}
		echo $html;

	}

	/**
	 *
	 * @param string $content
	 * @param int $post_id
	 * @param int $thumbnail_id
	 * @return string
	 */
	public function filter_featured_image_admin_text( $content, $post_id, $thumbnail_id ) {

		if ( get_post_field('post_type', $post_id) === 'woocg-post' && $thumbnail_id == null ) {
			$content .= wpautop(__('If you want to show an image next to the gateway\'s name on the frontend, select an image.', 'woo-custom-gateway'));
		}
		return $content;

	}

	/**
	 *
	 * @param string $input
	 * @param \WP_Post $post
	 * @return string
	 */
	public function custom_enter_title( $input, $post ) {

		if ( 'woocg-post' === $post->post_type ) {
			$input = __('Payment Method Name', 'woo-custom-gateway');
		}

		return $input;

	}

	public function add_column_data( $column, $post_id ) {

		switch ( $column ) {
			case 'thumbnail':
				echo the_post_thumbnail('thumb');
				break;
		}

	}

	public function add_columns( $columns ) {

		$columns['thumbnail'] = __('Thumbnail', 'woo-custom-gateway');

		return $columns;

	}

	/**
	 *
	 * @param array $actions
	 * @param \WP_Post $post
	 * @return string
	 */
	public function post_row_actions( $actions, $post ) {

		if ( $post->post_type === 'woocg-post' ) {

			$args = array( "page" => "wc-settings", "tab" => "checkout", "section" => $post->post_type . "-" . $post->ID);

			$link = add_query_arg($args, admin_url('admin.php'));

			$actions['settings'] = '<a href="' . $link . '">' . __('Settings', 'woo-custom-gateway') . '</a>';
		}
		return $actions;

	}

	/**
	 *
	 * @param int $postid
	 */
	public function on_delete_method( $postid ) {

		$method = new WC_Woo_Custom_Gateway($postid);

		delete_option($method->get_option_key());

	}
}


