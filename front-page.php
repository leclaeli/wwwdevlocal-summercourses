<?php
/**
 * Template Name: Front-page
 *
 * Description: Displays a full-width front page.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>
	<div id="content-top" class="content-area">
		<?php 
	    echo do_shortcode("[metaslider id=30]"); 
	?>
	</div>
	
	<div id="content" class="content-area">
		<div id="primary" class="site-content" role="main">
			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php get_sidebar( 'front-page-banner' ); ?>
				
					<div class="entry-content">
						
						<?php wp_reset_query(); the_content(); ?>
						<?php
							$args = array(
								//Type & Status Parameters
								'post_type'   => 'cpt-courses',
							);
	
						$the_query = new WP_Query( $args );
						// The Loop
						if ( $the_query->have_posts() ) { ?>
							<div id="courses">
								<ul>
								<?php while ( $the_query->have_posts() ) {
									$the_query->the_post();
									if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								?>
									
								<li id="<?php the_ID(); ?>">
									<a href='<?php the_permalink(); ?>'>
										<?php the_post_thumbnail('home_thumb'); ?>
										<span><?php the_title(); ?></span>
									</a>
								</li>
								<?php
								}
								} ?>
								</ul>
							</div>
						<?php
						} else {
							// no posts found
						}
						/* Restore original Post Data */
						wp_reset_postdata();
						?>
						
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div><!-- .entry-content -->

				</article><!-- #post -->
				
			<?php endwhile; ?>

		</div><!-- #primary -->
		
		
		<?php get_sidebar( 'subsidiary' ); ?>
		
	</div><!-- #content -->
	
	<div id="content-bottom" class="content-area">
		<?php
			$featured_args = array(
				//Type & Status Parameters
				'post_type'   => 'post',
				'cat'		  => 5
			);

		$the_query = new WP_Query( $featured_args );
		// The Loop
		if ( $the_query->have_posts() ) { ?>
			<div id="featured-slider">
				<ul>
				<?php while ( $the_query->have_posts() ) {
					$the_query->the_post();
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
				?>
					<li id="<?php the_ID(); ?>">
						<a href='<?php the_permalink(); ?>'>
							<?php the_post_thumbnail('home_crop'); ?>
							<?php // $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
						</a>
						<div id="course-caption">
							<span><?php the_title(); ?></span>
						</div>
					</li>
				<?php
				}
				} ?>
				</ul>
			</div>
		<?php
		} else {
			// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		?>

	</div>
	
<?php get_footer(); ?>