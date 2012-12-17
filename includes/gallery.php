<?php
global $woo_options, $post, $post_settings;

/*-----------------------------------------------------------------------------------*/
/* Variables and general gallery setup. */
/*-----------------------------------------------------------------------------------*/

$html = '';
$pagination_html = '';
$container_class = 'slides_container';
$post_meta = get_post_custom( $post->ID );

$settings = array();

$settings['use_timthumb'] = true; 		// Set to false to disable for this section of theme. Images will be downsized instead of resized to 640px width
$settings['limit'] = 20; 				// Number of maximum attachments to get 
$settings['photo_size'] = 'large';		// The WP "size" to use for the large image
$settings['width'] = 500;				// Default width
$settings['height'] = 600;				// Default height
$settings['thumb_width'] = 150;			// Default thumbnail width
$settings['thumb_height'] = 150;		// Default thumbnail height
$settings['use_height'] = false;		// Use height value
$settings['post_id'] = get_the_ID();	// Post ID to get the attachments for
$settings['embed'] = '';				// Determine whether or not the post has an embedded video
$settings['use_embed'] = false;			// Determine whether to display the embed code, if one exists, in place of the post gallery/image.

$dimensions = woo_portfolio_image_dimensions( $woo_options['woo_layout'], $woo_options['woo_layout_width'] );

$settings['width'] = $dimensions['width'];
$settings['height'] = $dimensions['height'];
$settings['thumb_width'] = $dimensions['thumb_width'];
$settings['thumb_height'] = $dimensions['thumb_height'];

if ( $settings['height'] > 0 ) { $settings['use_height'] = true; }

if ( ( $post_settings['embed'] != '' ) ) {
	$settings['use_embed'] = true;
}

$embed_args = 'width=' . ( $settings['width'] - 6 ); // Cater for the 3px border.

// Look for a video embed code.
$embed = woo_embed( $embed_args );
if ( ( $embed != '' ) && ( $settings['use_embed'] == true ) ) {
	$settings['width'] = $settings['width'] - 6; // Cater for the 3px border.
	$settings['embed'] = $embed;
	$container_class = 'video_container'; // Change the container class to be specific to videos.
}

// Allow child themes and plugins to filter these settings on a per-post basis.
$settings = apply_filters( 'woo_post_gallery_settings', $settings, $settings['post_id'] );

/*-----------------------------------------------------------------------------------*/
/* Process code - Setup the query arguments and get the attachmented images. */
/*-----------------------------------------------------------------------------------*/

$images = array(); // Default value, to prevent images from displaying if we have an embedded video.
if ( ( $settings['embed'] != '' ) && ( $settings['use_embed'] == true ) ) {
	
	$html = $settings['embed'];
	
} else {
	
	$images = $post_settings['gallery'];

} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* Generate the HTML to be outputted, if applicable. */
/*-----------------------------------------------------------------------------------*/

if ( ! empty( $images ) ) {

	$counter = 0;
	
	$main_css_class = '';
	
	$slide_container_class = 'slide';
	
	if ( count( $images ) == 1 ) {
		$slide_container_class = 'image';
		$main_css_class = ' single-image';
	}
	
	foreach ( $images as $k => $img ) {
		$counter++;
		
		$caption = '';
		$title = '';
		$src = '';
		$img_url = '';
		$img_atts = ' class="single-photo"';
		
		/* Set the position of all non-first slides to "out of the view", while loading.
		This gets overridden by loopedSlider when the gallery is fully loaded.
		This is to prevent other images with longer heights than the first, from displaying
		underneath the first while the gallery is loading. */
		
		$style = '';
		
		$position_setting = $settings['width'] + 6;

		if ( $counter == 1 ) {} else {
		
			$style = ' style="position: absolute; left: -' . $position_setting . 'px;"';
		
		} // End IF Statement
		
		// Setup the caption text, with a filter.
		if ( $img['caption'] != '' ) {
			$caption = apply_filters( 'woo_post_gallery_image_caption', '<span class="photo-caption">' . $img['caption'] . '</span>', $img['id'] );
			
			$img_atts .= ' alt="' . strip_tags( $caption ) . '"';
			$title = ' title="' . strip_tags( $caption ) . '"';
		} else {
			$title = 'title="' . get_the_title( $img['id'] ) . '"';
		}
		
		// Setup the image source, with a filter.
		$src = $img['url'];
		
		// Setup "template" for displaying each slide.
		$rel = 'lightbox';
		if ( count( $images ) > 1 ) {
		   $rel .= '[' . $post->ID . ']';
		}
		$before = '<div class="' . $slide_container_class . '"' . $style . '><a href="'. $src .'" rel="' . $rel . '" class="thickbox"' . $title . '>'; 
		$after = '</a>' . "\n" . $caption . '</div><!--/.slide-->' . "\n";
		
		$before_thumb = '<li style="width: ' . $settings['thumb_width'] . 'px; height: ' . $settings['thumb_height'] . 'px;"><a href="#">';
		$after_thumb = '</a></li>' . "\n";
		
		// Add the HTML to our main HTML to be outputted.
		$html .= $before . woo_image( 'noheight=true&return=true&src=' . $src . '&width=' . $settings['width'] ) . $after;
		if ( count( $images ) > 1 ) { $pagination_html .= $before_thumb . woo_image( 'return=true&src=' . $src . '&width=' . $settings['thumb_width'] . '&height=' . $settings['thumb_height'] ) . $after_thumb; } // End IF Statement
	}
	

} // End IF Statement

if ( $html != '' ) {
?>
	<!-- Start Photo Slider -->
	<div id="post-gallery" class="gallery<?php echo $main_css_class; ?>">
	    <div class="<?php echo $container_class; ?>">
	    	<?php echo $html; // This will show the large photo in the slider ?>
	    </div>
	    <div class="pagination">
		    <ul>
		    <?php echo $pagination_html; ?>
		    </ul>
	    </div><!--/.pagination-->
	<div class="fix"></div>
	</div>
	<!-- End Photo Slider -->
<?php
} // End IF Statement
?>
<?php if ( $counter > 1 ) { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery( '#post-gallery' ).slides({
		generateNextPrev: false,
		generatePagination: false, 
		autoHeight: true, 
		pagination: true, 
		paginationClass: 'pagination', 
		slidesLoaded: function () { jQuery( '#post-gallery .slides_control' ).css( 'height', jQuery( '#post-gallery .slide:first img' ).height() ); }
  	});
  	
  	if ( jQuery( '.pagination ul li' ).length >= 3 ) {
  	
	  	jQuery( '.pagination ul' ).jcarousel({
			visible: 3, 
			scroll: 3, 
			wrap: 'circular'
		});
		// Add class for Web Symbols font-face
		jQuery( '.jcarousel-next, .jcarousel-prev' ).addClass('icon');
		// Make sure the original click event doesn't fire on our slider navigation.
		jQuery( '.pagination a.next, .pagination a.previous' ).click( function ( e ) {
			return false;
		});
	
	}
});
</script>
<?php } // End IF Statement ?>