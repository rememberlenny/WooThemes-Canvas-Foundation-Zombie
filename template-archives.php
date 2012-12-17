<?php
/**
 * Template Name: Archives Page
 *
 * The archives page template displays a conprehensive archive of the current
 * content of your website on a single page. 
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 get_header();
?>       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="page row">

    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <div id="main">
                        
				<?php woo_loop_before(); ?>
                <!-- Post Starts -->
                <?php woo_post_before(); ?>
                <div <?php post_class(); ?>>
                    
                    <?php woo_post_inside_before(); ?>
    
                    <h2 class="title"><?php the_title(); ?></h2>
                    
                    <div class="entry">
                    
                        <h3><?php _e( 'The Last 30 Posts', 'woothemes' ); ?></h3>                                             
                        <ul>											  
                            <?php $saved = $wp_query; query_posts( 'showposts=30' ); ?>		  
                            <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>
                                <?php $wp_query->is_home = false; ?>	  
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php echo $post->comment_count; ?> <?php _e( 'comments', 'woothemes' ); ?></li>
                            <?php } } $wp_query = $saved; ?>					  
                        </ul>											  
                                                                          
                        <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>	  
                                                                          
                        <ul>											  
                            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>	
                        </ul>											  
                                                                          
                        <h3><?php _e( 'Monthly Archives', 'woothemes' ); ?></h3>
                                                                          
                        <ul>											  
                            <?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>	
                        </ul>
    
                    </div><!-- /.entry -->
                                
                    <?php woo_post_inside_after(); ?>
    
                </div><!-- /.post -->                 
                <?php woo_post_after(); ?>
                    
            </div><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
    
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
		
<?php get_footer(); ?>