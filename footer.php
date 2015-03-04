<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			
			<div class="site-info">
			
				<div class="meta">
				
					<h2><?php echo get_logotype(); ?></h2>
			
					<?php echo get_social_media(); ?>
					
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<?php error_reporting(0); ?>
						<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'footer-nav', 'fallback_cb' => 'false', 'depth' => '1' ) ); ?>
						<?php wp_debug_mode(); ?>
					<?php endif; ?>
			
					<p class="footer-copyright">&copy;<?php echo date("Y"); ?> University of Wisconsin-Milwaukee</p>
				
				</div>
				
				<div class="footer-tagline"></div>
				
			</div><!-- .site-info -->
			
			<div class="backtotop"><a href="#" title="Back to top"><i class="fa fa-chevron-up"></i></a></div>
			
		</footer><!-- #colophon -->
	</div><!-- #page -->
	<div id="slideout-hover">
		<div class="hover-details-content">
		</div>
	</div>
		
	<?php wp_footer(); ?>
</body>
</html>