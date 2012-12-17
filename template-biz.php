<?php
/**
 * Template Name: Business
 *
 * The business page template displays your posts with a "business"-style
 * content slider at the top.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $wp_query;
 get_header();

 $page_template = woo_get_page_template();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="twelve business">

    	<div id="main-sidebar-container">            <!-- #main Starts -->

    		<div id="slider-home" class="slides push-two eight columns pull-two text-center">
				<h2 class="title"><?php the_field('slide_maintitle'); ?></h2>
				<h3><?php the_field('slide_subtitle'); ?></h3>
				<div class="four columns">
					<div class="text-center category-option link-block">
						<a href="<?php the_field('home_link_1')?>">
							<div><img class="round-image" src="<?php the_field('thumb_fiber_image'); ?>" alt="" /></div>
								<h2><?php the_field('title1')?></h2>
							</div>
						</a>
					</div>
					<div class="four columns">
						<a href="<?php the_field('home_link_2')?>">
							<div class="text-center category-option link-block">
								<div><img class="round-image" src="<?php the_field('thumb_kit_image'); ?>" alt="" /></div>
								<h2><?php the_field('title2')?></h2>
							</div>
						</a>
					</div>
					<div class="four columns">
						<a href="<?php the_field('home_link_3')?>">	
							<div class="text-center category-option link-block">
								<div><img class="round-image" src="<?php the_field('thumb_tool_image'); ?>" alt="" /></div>
								<h2><?php the_field('title3')?></h2>
							</div>
						</a>
					</div>
				</div>
			</div>
    		
            <div id="mailbox" class="">
	            <!-	Gravity Forms Code Goes in template-biz.php in #mailbox ->
            </div>

            <?php woo_main_before(); ?>


							<?php while ( have_posts() ) : the_post(); ?>

							<div class="">
		<div class="twelve sliderfollow">
			
			<div class="twelve columns linkBlock">
				<div class="row">
					<div class="six columns">
						<div class="linkModule">
							<div class="row">
								<div class="four columns mobile-four">
									<h3><a href="<?php the_field('home_link_1')?>"><?php the_field('title1'); ?></a></h3>
								</div>
								
							</div>
							<p><?php the_field('description1'); ?></p>
							<a href="<?php the_field('home_link_1')?>" class="text-center  large button" title="<?php the_field('title1'); ?> button"><?php the_field('view_catalog_button1'); ?></a>
						</div>
					</div>
					<div class="six columns hide-for-small">
						<img src="<?php the_field('image1'); ?>" class="" />
					</div>
				</div>
			</div>
			<!-- End First -->
			<!-- Second -->
			<div class="twelve columns linkBlock">
				<div class="row">
					<div class="push-six six columns">
						<div class="linkModule">
							<div class="row">
								<div class="four columns mobile-four">
									<h3><a href="<?php the_field('home_link_2')?>"><?php the_field('title2'); ?></a></h3>
								</div>
								
							</div>
							<p><?php the_field('description2'); ?></p>
							<a href="<?php the_field('home_link_2')?>" class="text-center   large button" title="<?php the_field('title2'); ?> button"><?php the_field('view_catalog_button2'); ?></a>
						</div>
					</div>
					<div class="pull-six six columns hide-for-small">
						<img src="<?php the_field('image2'); ?>" class="" />
					</div>
				</div>
			</div>
			<!-- End Second -->
			<!-- Third -->
			<div class="twelve columns linkBlock block-last">
				<div class="row">
					<div class="six columns">
						<div class="linkModule">
							<div class="row">
								<div class="four columns mobile-four">
									<h3><a href="<?php the_field('home_link_3')?>"><?php the_field('title3'); ?></a></h3>
								</div>
								
							</div>
							<p><?php the_field('description3'); ?></p>
							<a href="<?php the_field('home_link_3')?>" class="text-center large button" title="<?php the_field('title3'); ?> button"><?php the_field('view_catalog_button3'); ?></a>
						</div>
					</div>
					<div class="six columns hide-for-small">
						<img src="<?php the_field('image3'); ?>" class="" />
					</div>
				</div>
			</div>
			<!-- End Third -->
		</div>


							<?php endwhile; // end of the loop. ?>


            <?php woo_main_after(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>