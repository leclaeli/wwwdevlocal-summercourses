<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="content" class="content-area">
		<div id="primary" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'cpt-courses' ); ?>
				<?php twentythirteen_post_nav(); ?>
				<?php comments_template(); ?>

			<?php endwhile; ?>

		</div><!-- #primary -->
		
		<?php get_sidebar( 'primary' ); ?>
		<?php get_sidebar( 'subsidiary' ); ?>	
		
	</div><!-- #content -->

<?php get_footer(); ?>