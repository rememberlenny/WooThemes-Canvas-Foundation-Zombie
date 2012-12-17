<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options;
?>

		<div id="footer" class="twelve">
			<div class="row footWrap ">	
		<?php
		 woo_footer_top();
		 	woo_footer_before();
		?>
		
				<?php woo_footer_inside(); ?>    
			    
				<div id="copyright" class="col-left">
					<?php woo_footer_left(); ?>
				</div>
				
				<div id="credit" class="col-right">
					<?php woo_footer_right(); ?>
				</div>
			</div>
				</div>
		</div><!-- /#footer  -->

	<?php woo_footer_after(); ?>    
	
	</div><!-- /#wrapper -->
	
	<div class="fix"></div><!--/.fix-->
	
	<?php wp_footer(); ?>
	<?php woo_foot(); ?>
	</body>
</html>