<?php
/**
 * Template Name: Plugins
 *
 * Displays a full-width page.
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

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php get_sidebar( 'content-top' ); ?>
				
					<header class="entry-header">
						
						<h1 class="entry-title"><?php the_title(); ?></h1>
						
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>

					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php showPlugins(); ?>
						<?php wp_reset_query(); the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
					
					<?php get_sidebar( 'content-bottom' ); ?>
					
				</article><!-- #post -->
				
				<?php comments_template(); ?>
			<?php endwhile; ?>

		</div><!-- #primary -->
		
		<?php get_sidebar( 'primary' ); ?>
		<?php get_sidebar( 'subsidiary' ); ?>
		
	</div><!-- #content -->

<?php get_footer(); ?>