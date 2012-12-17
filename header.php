<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 // Setup the tag to be used for the header area (`h1` on the front page and `span` on all others).
 $heading_tag = 'span';
 if ( is_home() OR is_front_page() ) { $heading_tag = 'h1'; }

 // Get our website's name, description and URL. We use them several times below so lets get them once.
 $site_title = get_bloginfo( 'name' );
 $site_url = home_url( '/' );
 $site_description = get_bloginfo( 'description' );

 global $woo_options;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_bloginfo( 'stylesheet_url' ) ); ?>" media="all" />
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php wp_head(); ?>
<?php woo_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="header" class="row twelve">
	<div id="logo">
		<?php
			$logo_url = $woo_options['woo_logo'];
			if ( is_ssl() ) $logo_url = str_replace( 'http', 'https', $logo_url );

			// Website heading/logo and description text.
			if ( isset($woo_options['woo_logo']) && $woo_options['woo_logo'] ) {
				echo '<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" /></a>' . "\n";
			} // End IF Statement

			echo '<' . $heading_tag . ' class="site-title"><a href="' . esc_url( $site_url ) . '">' . $site_title . '</a></' . $heading_tag . '>' . "\n";
			if ( $site_description ) { echo '<span class="site-description">' . $site_description . '</span>' . "\n"; }
		?>
		</div><!-- /#logo -->
  <h3 class="nav-toggle icon"><a href="#navigation"><?php _e( 'Navigation', 'woothemes' ); ?></a></h3>
  	<div id="navigation" class="">
		<?php woo_nav_inside(); ?>
		<?php
		if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fr', 'theme_location' => 'primary-menu' ) );
		} else {
		?>
		<ul id="main-nav" class="nav fr">
			<?php
			if ( get_option( 'woo_custom_nav_menu' ) == 'true' ) {
				if ( function_exists( 'woo_custom_navigation_output' ) ) { woo_custom_navigation_output( 'name=Woo Menu 1' ); }
			} else { ?>

				<?php if ( is_page() ) { $highlight = 'page_item'; } else { $highlight = 'page_item current_page_item'; } ?>
				<li class="<?php echo esc_attr( $highlight ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
				<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
			<?php } ?>
		</ul><!-- /#nav -->
		<?php } ?>
	</div><!-- /#navigation -->
	<?php woo_header_before(); ?>



<?php
		global $woo_options;
		woo_nav_before();
?>
</div>

<div id="wrapper" class="twelve">
	
		<?php woo_header_inside(); ?>
	
	<?php woo_header_after(); ?>

