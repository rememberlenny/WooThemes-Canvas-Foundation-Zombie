<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php

/*-------------------------------------------------------------------------------------

TABLE OF CONTENTS

- Redirect to "Theme Options" screen (hooked onto woo_theme_activate at 10).
- Flush rewrite rules to refresh permalinks for custom post types, etc.
- Show Options Panel after activate
- Admin Backend
	- Setup Custom Navigation
- Output HEAD - woothemes_wp_head()
	- Output alternative stylesheet
	- Output custom favicon
	- Load textdomains
	- Output CSS from standarized styling options
	- Output shortcodes stylesheet
	- Output custom.css
- Post Images from WP2.9+ integration
- Enqueue comment reply script

-------------------------------------------------------------------------------------*/

define( 'THEME_FRAMEWORK', 'woothemes' );

/*-----------------------------------------------------------------------------------*/
/* Redirect to "Theme Options" screen (hooked onto woo_theme_activate at 10). */
/*-----------------------------------------------------------------------------------*/
add_action( 'woo_theme_activate', 'woo_themeoptions_redirect', 10 );

function woo_themeoptions_redirect () {
	// Do redirect
	header( 'Location: ' . admin_url() . 'admin.php?page=woothemes&activated=true' );
} // End woo_themeoptions_redirect()

/*-----------------------------------------------------------------------------------*/
/* Flush rewrite rules to refresh permalinks for custom post types, etc. */
/*-----------------------------------------------------------------------------------*/

function woo_flush_rewriterules () {
	flush_rewrite_rules();
} // End woo_flush_rewriterules()

/*-----------------------------------------------------------------------------------*/
/* Add default options and show Options Panel after activate  */
/*-----------------------------------------------------------------------------------*/
global $pagenow;

if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
	// Call action that sets.
	add_action( 'admin_head','woo_option_setup' );
	
	// Flush rewrite rules.
	add_action( 'admin_head', 'woo_flush_rewriterules', 9 );
	
	// Custom action for theme-setup (redirect is at priority 10).
	do_action( 'woo_theme_activate' );
}


if ( ! function_exists( 'woo_option_setup' ) ) {
	function woo_option_setup(){

		//Update EMPTY options
		$woo_array = array();
		add_option( 'woo_options', $woo_array );

		$template = get_option( 'woo_template' );
		$saved_options = get_option( 'woo_options' );

		foreach ( (array) $template as $option ) {
			if ($option['type'] != 'heading'){
				$id = $option['id'];
				$std = isset( $option['std'] ) ? $option['std'] : NULL;
				$db_option = get_option($id);
				if (empty($db_option)){
					if (is_array($option['type'])) {
						foreach ($option['type'] as $child){
							$c_id = $child['id'];
							$c_std = $child['std'];
							$db_option = get_option($c_id);
							if (!empty($db_option)){
								update_option($c_id,$db_option);
								$woo_array[$id] = $db_option;
							} else {
								$woo_array[$c_id] = $c_std;
							}
						}
					} else {
						update_option($id,$std);
						$woo_array[$id] = $std;
					}
				} else { //So just store the old values over again.
					$woo_array[$id] = $db_option;
				}
			}
		}
		
		// Allow child themes/plugins to filter here.
		$woo_array = apply_filters( 'woo_options_array', $woo_array );
		
		update_option( 'woo_options', $woo_array );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woothemes_admin_head' ) ) {
	function woothemes_admin_head() {
	    
	}
}
add_action( 'admin_head', 'woothemes_admin_head', 10 );


/*-----------------------------------------------------------------------------------*/
/* Output HEAD - woothemes_wp_head */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woothemes_wp_head' ) ) {
	function woothemes_wp_head() {

		do_action( 'woothemes_wp_head_before' );

		// Output alternative stylesheet
		if ( function_exists( 'woo_output_alt_stylesheet' ) )
			woo_output_alt_stylesheet();

		// Output custom favicon
		if ( function_exists( 'woo_output_custom_favicon' ) )
			woo_output_custom_favicon();

		// Output CSS from standarized styling options
		if ( function_exists( 'woo_head_css' ) )
			woo_head_css();

		// Output shortcodes stylesheet
		if ( function_exists( 'woo_shortcode_stylesheet' ) )
			woo_shortcode_stylesheet();

		// Output custom.css
		if ( function_exists( 'woo_output_custom_css' ) )
			woo_output_custom_css();
			
		do_action( 'woothemes_wp_head_after' );
	} // End woothemes_wp_head()
}
add_action( 'wp_head', 'woothemes_wp_head', 10 );

/*-----------------------------------------------------------------------------------*/
/* Output alternative stylesheet - woo_output_alt_stylesheet */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_output_alt_stylesheet' ) ) {
	function woo_output_alt_stylesheet() {
		$style = '';

		if ( isset( $_REQUEST['style'] ) ) {
			// Sanitize requested value.
			$requested_style = esc_attr( strtolower( strip_tags( trim( $_REQUEST['style'] ) ) ) );
			$style = $requested_style;
		}

		echo "\n" . "<!-- Alt Stylesheet -->\n";
		// If we're using the query variable, be sure to check for /css/layout.css as well.
		if ( $style != '' ) {
			if ( strtolower( $style ) == 'default' ) {
				if ( file_exists( get_template_directory() . '/css/layout.css' ) ) {
					echo '<link href="' . esc_url( get_template_directory_uri() . '/css/layout.css' ) . '" rel="stylesheet" type="text/css" />' . "\n";
				}
				echo '<link href="' . esc_url( get_stylesheet_uri() ) . '" rel="stylesheet" type="text/css" />' . "\n";
			} else {
				echo '<link href="' . esc_url( get_template_directory_uri() . '/styles/' . $style . '.css' ) . '" rel="stylesheet" type="text/css" />' . "\n";
			}
		} else {
			$style = get_option( 'woo_alt_stylesheet' );
			$style = esc_attr( strtolower( strip_tags( trim( $style ) ) ) );
			if( $style != '' ) {
				echo '<link href="'. esc_url( get_template_directory_uri() . '/styles/'. $style ) . '" rel="stylesheet" type="text/css" />' . "\n";
			} else {
				echo '<link href="'. esc_url( get_template_directory_uri() . '/styles/default.css' ) . '" rel="stylesheet" type="text/css" />' . "\n";
			}
		}
	} // End woo_output_alt_stylesheet()
}

/*-----------------------------------------------------------------------------------*/
/* Output favicon link - woo_custom_favicon() */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_output_custom_favicon' ) ) {
	function woo_output_custom_favicon() {
		// Favicon
		$favicon = '';
		$favicon = get_option( 'woo_custom_favicon' );
		
		// Allow child themes/plugins to filter here.
		$favicon = apply_filters( 'woo_custom_favicon', $favicon );
		if( $favicon != '' ) {
			echo "\n" . "<!-- Custom Favicon -->\n";
	        echo '<link rel="shortcut icon" href="' .  esc_url( $favicon )  . '"/>' . "\n";
	    }
	} // End woo_output_custom_favicon()
}

/*-----------------------------------------------------------------------------------*/
/* Load textdomain - woo_load_textdomain() */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_load_textdomain' ) ) {
	function woo_load_textdomain() {

		load_theme_textdomain( 'woothemes' );
		load_theme_textdomain( 'woothemes', get_template_directory() . '/lang' );
		if ( function_exists( 'load_child_theme_textdomain' ) )
			load_child_theme_textdomain( 'woothemes' );

	}
}

add_action( 'init', 'woo_load_textdomain', 10 );

/*-----------------------------------------------------------------------------------*/
/* Output CSS from standarized options */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_head_css' ) ) {
	function woo_head_css() {

		$output = '';
		$text_title = get_option( 'woo_texttitle' );
		$tagline = get_option( 'woo_tagline' );
	    $custom_css = get_option( 'woo_custom_css' );

		$template = get_option( 'woo_template' );
		if (is_array($template)) {
			foreach($template as $option){
				if(isset($option['id'])){
					if($option['id'] == 'woo_texttitle') {
						// Add CSS to output
						if ( $text_title == 'true' ) {
							$output .= '#logo img { display:none; } .site-title { display:block!important; }' . "\n";
							if ( $tagline == "false" )
								$output .= '.site-description { display:none!important; }' . "\n";
							else
								$output .= '.site-description { display:block!important; }' . "\n";
						}
					}
				}
			}
		}

		if ( $custom_css != '' ) {
			$output .= $custom_css . "\n";
		}

		// Output styles
		if ( $output != '' ) {
			$output = strip_tags($output);
			echo "<!-- Options Panel Custom CSS -->\n";
			$output = "<style type=\"text/css\">\n" . $output . "</style>\n\n";
			echo stripslashes( $output );
		}

	} // End woo_head_css()
}



/*-----------------------------------------------------------------------------------*/
/* Output custom.css - woo_custom_css() */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_output_custom_css' ) ) {
	function woo_output_custom_css() {

		$theme_dir = get_template_directory_uri();
		
		if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/custom.css' ) ) {
			$theme_dir = get_stylesheet_directory_uri();
		}
		// Custom.css insert
		echo "\n" . "<!-- Custom Stylesheet -->\n";
		echo '<link href="'. esc_url( $theme_dir . '/custom.css' ) . '" rel="stylesheet" type="text/css" />' . "\n";

	} // End woo_output_custom_css()
}

/*-----------------------------------------------------------------------------------*/
/* Post Images from WP2.9+ integration /*
/*-----------------------------------------------------------------------------------*/
if( function_exists( 'add_theme_support' ) ) {
	if( get_option( 'woo_post_image_support' ) == 'true' ) {
		add_theme_support( 'post-thumbnails' );
		// set height, width and crop if dynamic resize functionality isn't enabled
		if ( get_option( 'woo_pis_resize' ) != 'true' ) {
			$thumb_width = get_option( 'woo_thumb_w' );
			$thumb_height = get_option( 'woo_thumb_h' );
			$single_width = get_option( 'woo_single_w' );
			$single_height = get_option( 'woo_single_h' );
			$hard_crop = get_option( 'woo_pis_hard_crop' );
			if($hard_crop == 'true') { $hard_crop = true; } else { $hard_crop = false; }
			set_post_thumbnail_size( $thumb_width, $thumb_height, $hard_crop ); // Normal post thumbnails
			add_image_size( 'single-post-thumbnail', $single_width, $single_height, $hard_crop );
		}
	}
}


/*-----------------------------------------------------------------------------------*/
/* Enqueue comment reply script */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_comment_reply' ) ) {
	function woo_comment_reply() {
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	} // End woo_comment_reply()
}
add_action( 'get_header', 'woo_comment_reply', 10 );
?>