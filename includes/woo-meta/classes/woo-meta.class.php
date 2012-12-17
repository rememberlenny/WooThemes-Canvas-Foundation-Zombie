<?php
/**
 * Meta Manager
 *
 * This is the meta manager class, containing all processing and setup functionality
 * for managing the metadata above and below blog post content.
 *
 * @package WooFramework
 * @subpackage Module
 * 
 *-----------------------------------------------------------------------------------

 CLASS INFORMATION

 Date Created: 2011-03-21.
 Author: Matty.
 Since: 4.0.0


 TABLE OF CONTENTS

 - var $plugin_prefix
 - var $plugin_path
 - var $plugin_url
 - var $version
 
 - var $admin_page
 - var $meta_areas
 
 - var $shortcodes
 
 - function Woo_Meta () (Constructor)
 - function init ()
 - function register_admin_screen ()
 - function admin_screen ()
 - function admin_screen_help ()
 - function enqueue_scripts ()
 - function enqueue_styles ()
 - function create_filters ()
 - function setup_shortcodes ()
 - function add_exporter_data ()

-----------------------------------------------------------------------------------*/

class Woo_Meta {

	/*----------------------------------------
	  Class Variables
	  ----------------------------------------
	  
	  * Setup of variable placeholders, to be
	  * populated when the constructor runs.
	----------------------------------------*/

	var $plugin_prefix;
	var $plugin_path;
	var $plugin_url;
	var $version;

	var $admin_page;
	var $meta_areas;
	
	var $shortcodes;

	/*----------------------------------------
 	  Constructor Function
 	  ----------------------------------------
 	  
 	  * Constructor function.
 	  * Sets up the class and registers
 	  * variable action hooks.
 	  
 	  * Params:
 	  * - String $plugin_prefix
 	  * - String $plugin_path
 	  * - String $plugin_url
 	----------------------------------------*/

	function Woo_Meta ( $plugin_prefix, $plugin_path, $plugin_url, $version ) {
	
		$this->plugin_prefix = $plugin_prefix;
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;
		$this->version = $version;
	
		$this->init();
	
	} // End Constructor
	
	/*----------------------------------------
 	  init()
 	  ----------------------------------------
 	  
 	  * This guy runs the show.
 	  * Rocket boosters... engage!
 	----------------------------------------*/
	
	function init () {
		
		// Create the necessary filters.
		add_action( 'after_setup_theme', array( &$this, 'create_filters' ), 10 );
		
		if ( is_admin() ) {
	
			// Register the admin screen.
			add_action( 'admin_menu', array( &$this, 'register_admin_screen' ), 20 );
			
			// Execute certain code only on the specific admin screen.
			if ( is_admin( $this->admin_page ) ) {
			
				// Add contextual help.
				add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
				
				// Setup default shortcodes for reference.
				$this->setup_shortcodes();
				
				// Stored data.
				$stored_values = get_option( $this->plugin_prefix . 'stored_meta' );
				
				// Setup meta areas.
				$this->meta_areas = array();
				
				$this->meta_areas['post_meta'] = array(
										'woo_filter_post_meta' => array(
												'title' => __( 'Above post content', 'woothemes' ), 
												'default' => '<span class="small">' . __( 'By', 'woothemes' ) . '</span> [post_author_posts_link] <span class="small">' . __( 'on', 'woothemes' ) . '</span> [post_date] <span class="small">' . __( 'in', 'woothemes' ) . '</span> [post_categories before=""] ' . ' [post_edit]', 
												'stored_value' => '', 
												'description' => __( 'Data above the content of your blog posts.', 'woothemes' )
											),
										'woo_post_more' => array(
												'title' => __( '"Read more" area below posts', 'woothemes' ), 
												'default' => '[view_full_article] &middot; [post_comments]', 
												'stored_value' => '', 
												'description' => __( 'Data below each blog post.', 'woothemes' )
											)
									);
									
				// Assigned stored data to the appropriate meta_area.
				foreach ( $this->meta_areas as $id => $arr ) {
					foreach ( $this->meta_areas[$id] as $k => $v ) {
						if ( is_array( $stored_values ) && array_key_exists( $k, $stored_values ) ) {
							$this->meta_areas[$id][$k]['stored_value'] = $stored_values[$k];
						} else {
							$this->meta_areas[$id][$k]['stored_value'] = $this->meta_areas[$id][$k]['default'];
						}
					}
				}
			
				// Make sure our data is added to the WooFramework settings exporter.
				add_filter( 'wooframework_export_query_inner', array( &$this, 'add_exporter_data' ) );
			
			}
		
		} // End IF Statement
	
	} // End init()

	function register_admin_screen () {
		
		if ( function_exists( 'add_submenu_page' ) ) {	
			
			$this->admin_page = add_submenu_page('woothemes', __( 'Meta Manager', 'woothemes' ), __( 'Meta Manager', 'woothemes' ), 'manage_options', 'woo-meta-manager', array( &$this, 'admin_screen' ) );
			
			// Admin screen logic.
			add_action( 'load-' . $this->admin_page, array( &$this, 'admin_screen_logic' ) );
			
			// Admin screen JavaScript.
			add_action( 'admin_print_scripts-' . $this->admin_page, array( &$this, 'enqueue_scripts' ) );
			
			// Admin screen CSS.
			add_action( 'admin_print_styles-' . $this->admin_page, array( &$this, 'enqueue_styles' ) );
			
			// TinyMCE JavaScript and init.
			add_action( 'admin_head-' . $this->admin_page, array( &$this, 'tinymce_headers' ) );
			
		}
	
	} // End register_admin_screen()
	
	/*----------------------------------------
 	  admin_screen()
 	  ----------------------------------------
 	  
 	  * Load the admin screen.
 	----------------------------------------*/
	
	function admin_screen () {
	
		// Keep the screen XHTML separate and load it from that file.
		include_once( $this->plugin_path . '/screens/admin.php' );
	
	} // End admin_screen()
	
	/*----------------------------------------
 	  admin_screen_help()
 	  ----------------------------------------
 	  
 	  * Contextual help on the admin screen.
 	----------------------------------------*/
	
	function admin_screen_help ( $contextual_help, $screen_id, $screen ) {
	
		// $contextual_help .= var_dump($screen); // use this to help determine $screen->id
		
		if ( $this->admin_page == $screen->id ) {
		
		$contextual_help =
		  '<p>' . __('Welcome to the Woo Meta Manager!', 'woothemes') . '</p>' .
		  '<p>' . __('Here are a few notes on using this screen.', 'woothemes') . '</p>' .
		  '<p>' . __('Fill in the area you\'d like to customise and hit the "Save All Changes" button. It\'s as easy as that!', 'woothemes') . '</p>' .
		  '<p><strong>' . __('For more information:', 'woothemes') . '</strong></p>' .
		  '<p>' . sprintf( __('<a href="%s" target="_blank">WooThemes Support Forums</a>', 'woothemes'), 'http://forum.woothemes.com/' ) . '</p>';
		
		} // End IF Statement
		
		return $contextual_help;
	
	} // End admin_screen_help()
	
	/*----------------------------------------
 	  admin_screen_logic()
 	  ----------------------------------------
 	  
 	  * The processing logic used on the
 	  * admin screen.
 	----------------------------------------*/
	
	function admin_screen_logic () {
		
		// Reset logic.
		
		$is_processed = false;
		
		if ( isset( $_POST['woometa_reset'] ) && check_admin_referer( 'woometa-options-update' ) ) {
			update_option( $this->plugin_prefix . 'stored_meta' );
			
			$is_processed = true;
			
			wp_redirect( admin_url( 'admin.php?page=woo-meta-manager&reset=true' ) );
		}
		
		// Save logic.
		
		if ( isset( $_POST['woometa_update'] ) && check_admin_referer( 'woometa-options-update' ) ) {
			
			$fields_to_skip = array( 'woometa_update', '_wp_http_referer', '_wpnonce' );
			
			$posted_data = $_POST;
			
			foreach ( $posted_data as $k => $v ) {
				if ( in_array( $k, $fields_to_skip ) ) {
					unset( $posted_data[$k] );
				} else {
					$posted_data[$k] = addslashes( stripslashes( $v ) );
				}
			}
			
			if ( is_array( $posted_data ) ) {
				$is_updated = update_option( $this->plugin_prefix . 'stored_meta', $posted_data );
				
				// Redirect to make sure the latest changes are reflected.
				wp_redirect( admin_url( 'admin.php?page=woo-meta-manager&updated=true' ) );
			}
			$is_processed = true;
		}
	
	} // End admin_screen_logic()
	
	/*----------------------------------------
 	  enqueue_scripts()
 	  ----------------------------------------
 	  
 	  * Enqueue the necessary JavaScript files.
 	----------------------------------------*/
	
	function enqueue_scripts () {

		wp_register_script( 'woo-meta-functions', $this->plugin_url . 'assets/js/functions.js', array( 'jquery' ), '1.0.0', true );
		
		wp_enqueue_script( 'woo-meta-functions' ); // The dependant JavaScript files will be enqueued automatically.
	
	} // End enqueue_scripts()
	
	/*----------------------------------------
 	  tinymce_headers()
 	  ----------------------------------------
 	  
 	  * Load the necessary files for using
 	  * the integrated tinyMCE editor.
 	----------------------------------------*/
	
	function tinymce_headers () {

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'jquery-color' );
		wp_enqueue_style( 'colors' );
		wp_print_scripts( 'editor' );
		if ( function_exists( 'add_thickbox' ) ) { add_thickbox(); }
		wp_enqueue_script( 'media-upload' );
		if ( function_exists( 'wp_tiny_mce' ) ) { wp_tiny_mce(); }
		wp_admin_css();
		wp_enqueue_script('utils');
		// do_action("admin_print_styles-post-php");
		// do_action('admin_print_styles');
	
	} // End tinymce_headers()
	
	/*----------------------------------------
 	  enqueue_styles()
 	  ----------------------------------------
 	  
 	  * Enqueue the necessary CSS files.
 	----------------------------------------*/
	
	function enqueue_styles () {
	
		wp_register_style( 'woo-admin-interface', get_template_directory_uri() . '/functions/admin-style.css' );
		
		wp_enqueue_style( 'woo-admin-interface' );
	
	} // End enqueue_styles()
	
	/*----------------------------------------
 	  create_filters()
 	  ----------------------------------------
 	  
 	  * Create filters using our saved content.
 	----------------------------------------*/
	
	function create_filters () {
	
		if ( ! is_admin() ) {
		
			$stored_meta = get_option( $this->plugin_prefix . 'stored_meta' );
			
			// Create the filter functions.
			if ( is_array( $stored_meta ) ) {
				foreach ( $stored_meta as $k => $v ) {
					$new_string = $v;
					
					$new_string = str_replace( '\"', '', $new_string );
					
					$content = '';
					
					add_filter( $k, create_function( "$content", "return '$new_string';" ), 12 );
				}
			}
		
		} // End IF Statement
	
	} // End create_filters()
	
	/*----------------------------------------
 	  setup_shortcodes()
 	  ----------------------------------------
 	  
 	  * Setup shortcodes for reference.
 	----------------------------------------*/
	
	function setup_shortcodes() {
	
		$this->shortcodes = array();
		$this->shortcodes['view_full_article'] = __( 'Link to read the full post.', 'woothemes' );
		$this->shortcodes['post_date'] = __( 'The post date.', 'woothemes' );
		$this->shortcodes['post_time'] = __( 'The post time.', 'woothemes' );
		$this->shortcodes['post_author_link'] = __( 'The post author (link to the author\'s website).', 'woothemes' );
		$this->shortcodes['post_author_posts_link'] = __( 'The post author (link to the author\'s posts archive).', 'woothemes' );
		$this->shortcodes['post_comments'] = __( 'Comments for the post.', 'woothemes' );
		$this->shortcodes['post_tags'] = __( 'Tags for the post.', 'woothemes' );
		$this->shortcodes['post_categories'] = __( 'Categories for the post.', 'woothemes' );
		$this->shortcodes['post_edit'] = __( '"Edit" link for the post.', 'woothemes' );
	
	} // End setup_shortcodes()
	
	/*----------------------------------------
 	  add_exporter_data()
 	  ----------------------------------------
 	  
 	  * Add our saved data to the WooFramework
 	  * data exporter.
 	----------------------------------------*/
	
	function add_exporter_data ( $data ) {
		
		$data .= " OR option_name = '" . $this->plugin_prefix . "stored_meta" . "'";
		
		return $data;
		
	} // End add_exporter_data()

} // End Class
?>