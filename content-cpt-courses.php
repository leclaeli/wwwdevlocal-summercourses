<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php endif; // is_single() ?>
	
	<!-- deleted .entry-meta -->
		
		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<?php if ( is_single() || is_home() ) : ?>
				<?php the_post_thumbnail(); ?>
			<?php else : ?>
				<a href="<?php echo get_permalink(); ?>">
				<?php the_post_thumbnail('thumbnail'); ?>
				</a>
			<?php endif; ?>	
		</div>
		<?php endif; ?>
		
		
	
	</header><!-- .entry-header -->
	

	<?php if ( is_search() || is_archive() )  : // Only display Excerpts for Search and Archives ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<h4><?php the_field( 'course_number_section' ); ?></h4>
			
			<?php 
			echo '<ul><li><b>Class Number:</b> '; 
			the_field( 'class_number' );
			echo '</li><li><b>Credits:</b> '; 
			the_field( 'number_of_credits' );
			echo '</li><li><b>Instructor:</b> '; 
			the_field( 'instructor' );
			if( get_field('start_date') ):
				echo '</li><li><b>Dates:</b> ';
			/*
			*  Create PHP DateTime object from Date Piker Value
			*  this example expects the value to be saved in the format: yymmdd (JS) = Ymd (PHP)
			*/
				$date = DateTime::createFromFormat('Y-m-d', get_field('start_date'));
				echo $date->format('m/d/y');
				echo '–';
				$date = DateTime::createFromFormat('Y-m-d', get_field('end_date'));
				echo $date->format('m/d/y');
			endif;
			if( get_field('course_syllabus') ):
				$file = get_field('course_syllabus');
				echo "</li><li><a href='$file[url]' target='_blank'>Course Syllabus (.pdf)</a>";
			endif;
			echo '</li></ul>';
			
			// echo $file['url'];
			// // view array of data
			// var_dump($file);
			?>

			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentythirteen' ) ); ?>
		</div>
			<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
			<?php get_template_part( 'author-bio' ); ?>
		<?php endif; ?>
	</footer><!-- .entry-meta -->
</article><!-- #post -->
