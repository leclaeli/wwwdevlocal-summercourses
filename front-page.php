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
	
	<?php 
	    // echo do_shortcode("[metaslider id=30]"); 
	?>

	<?php
		$featured_args = array(
			//Type & Status Parameters
			'post_type'   		=> 'cpt-courses',
			'cat'		  		=> 5,
			'posts_per_page'	=> 4,
			'orderby'			=> 'rand',
		);

	$the_query = new WP_Query( $featured_args );
	// The Loop
	if ( $the_query->have_posts() ) { ?>
	<?php $post = $posts[0]; $c=0;?>
		<?php while ( $the_query->have_posts() ) { ?>
		<?php
		$the_query->the_post();
		$c++;
		if ( $c == 1 ) { ?>
			<div id="top-container" class="content-area">
				<div id="top-content">
					<div id="featured-slider">
						<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
						?>	
						<a href='<?php the_permalink(); ?>'>
							<?php the_post_thumbnail('home_featured_img'); ?>
							<?php // $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
						</a>
							<?php
							} //end if ?> 
					</div>
					<div id="top-right-featured">
						<div id="course-summary">
							<?php
							echo '<h3>' . get_the_title() . '</h3>'; ?>
							<p id="course-number"><?php the_field( 'course_number_section' ); ?></p>
							<?php
							echo '<p id="course-description">' . get_the_content() . '</p>';
							?>
						</div>
						<div id="course-details">
							<?php 
							echo '<ul><li>Class Number: '; 
							the_field( 'class_number' );
							echo '</li><li>Credits: '; 
							the_field( 'number_of_credits' );
							echo '</li><li>Instructor: '; 
							the_field( 'instructor' );
							echo '</li></ul>';
							?>
						</div>
					</div>
				</div>
			</div> <!-- end top-containter -->
			<div id="sub-top-container" class="content-area">
				<div id="sub-top-content">
		<?php 
		} else { ?>
				
					<div id="<?php the_ID(); ?>" class="sub-featured-course">
						<a href='<?php the_permalink(); ?>'>
						<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
						?>
							<?php the_post_thumbnail('home_featured_img'); ?>
							<?php // $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
						
							<?php
							} //end if ?>
						<?php echo '<h4><span>' . get_the_title() . '</span></h4>'; ?>
						</a>	
					</div>
		<?php } ?>
		<?php } // end while ?>
				</div>
			</div>
	<?php
	} else {
		// no posts
	} // end if have_posts()
	 
	/* Restore original Post Data */
	wp_reset_postdata();
	?>

	<div id="courses-container" class="content-area">
		<div id="courses-content">
		<?php
			$args = array(
				//Type & Status Parameters
				'post_type'   => 'cpt-courses',
			);
			$the_query = new WP_Query( $args );
			// The Loop
			if ( $the_query->have_posts() ) { ?>
			<?php $c = 0; ?>
				<div id="courses">
					<ul>
					<?php while ( $the_query->have_posts() ) {
						$the_query->the_post();
						if ( $c == 4 ) {
							$c = 0;
						}
						$c++;
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					?>
					<?php 
						if ( $c == 1 ) {
							$col_class = "first-of-four";
						} else if ( $c == 4 ) {
							$col_class = "last-of-four";
						} else {
							$col_class = "middle";
						}
					 ?>	
					<li id="<?php the_ID(); ?>" class="<?php echo $col_class; ?>">
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
		</div>
	</div>



	
	
	<div id="content" class="content-area">
		<div id="primary" class="site-content" role="main">
			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php get_sidebar( 'front-page-banner' ); ?>
				
					<div class="entry-content">
						
						<?php wp_reset_query(); the_content(); ?>
						
					</div><!-- .entry-content -->

				</article><!-- #post -->
				
			<?php endwhile; ?>

		</div><!-- #primary -->
		
		
		<?php get_sidebar( 'subsidiary' ); ?>
		
	</div><!-- #content -->
	
	
<?php get_footer(); ?>