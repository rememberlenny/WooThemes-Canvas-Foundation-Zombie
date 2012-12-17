<?php
/**
 * Template Name: Image Gallery
 *
 * The image gallery page template displays a styled
 * image grid of a maximum of 60 posts with images attached.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 get_header();
?> 
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="row">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <div id="main">      
                                                                                
				<?php woo_loop_before(); ?>
				
                <!-- Post Starts -->
                <?php woo_post_before(); ?>
                <div class="post">
    
                    <?php woo_post_inside_before(); ?>
    
                    <h1 class="title"><?php the_title(); ?></h1>
                    
                    <div class="entry">
                    <?php $saved = $wp_query; query_posts( 'showposts=60' ); ?>
                    <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>				
                        <?php $wp_query->is_home = false; ?>
    
                        <?php woo_get_image( 'image', 100, 100, 'thumbnail alignleft' ); ?>
                    
                    <?php } } $wp_query = $saved; ?>	
                    <div class="fix"></div>
                    </div>
    
                    <?php woo_post_inside_after(); ?>
    
                </div><!-- /.post -->
                <?php woo_post_after(); ?>
                <div class="fix"></div>                
                                                                
            </div><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
    
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
		
<?php get_footer(); ?>