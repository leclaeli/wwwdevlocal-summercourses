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
            'post_type'         => 'cpt-courses',
            'category_name'     => 'featured',
            'orderby'           => 'rand',
        );

    $the_query = new WP_Query( $featured_args );
    // The Loop

    if ( $the_query->have_posts() ) { ?>
        <div id="top-container" class="content-area">
            <div id="featured-slider">
                <ul>
                <?php while ( $the_query->have_posts() ) { ?>
                <?php
                $the_query->the_post();
                ?>
                    <li>
                        <div id="slider-background">
                            <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                            ?>  
                            <div id="slider-image">
                                <a href='<?php the_permalink(); ?>'>
                                    <?php the_post_thumbnail('home_featured_img'); ?>
                                    <?php // $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
                                </a>
                            </div>
                                <?php
                                } //end if ?> 
                            <div id="gradient-left">
                                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/gradient.png" alt="Gradient Background">
                            </div>
                            <div id="gradient-right">
                                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/gradient-right.png" alt="Gradient Background">
                            </div>

                        </div>
                    
                        <div id="slider-content">
                            <div id="course-summary">
                                <?php
                                echo '<h3>' . get_the_title() . '</h3>'; ?>
                                
                                <?php
                                echo '<p id="course-description">' . get_the_excerpt() . '</p>';
                                ?>
                                <div id="course-details">
                                    <p id="course-number"><?php the_field( 'course_number_section' ); ?></p>
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
                            <div id="course-link">
                                <a href='<?php the_permalink(); ?>'>View Course</a>
                            </div>
                            
                        </div>
                    </li>
                <?php } // end while ?>
                </ul>
                <div id="slide-controls">
                    <div id="previous">previous</div>
                    <div id="next">next</div>
                </div>
            </div>
            <div class="clear"></div>
    <?php
    } else {
        // no posts
    } // end if have_posts()
     
    /* Restore original Post Data */
    wp_reset_postdata();
    ?>
    </div> <!-- end top-containter -->

        
            <div id="sub-top-container" class="content-area">
                <div id="sub-top-content">
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
                </div>
            </div>


    <div id="courses-container" class="content-area">
        <div id="courses-content">
        <h2 id="browse">Browse Featured Courses</h2>
        <?php
            $args = array(
                //Type & Status Parameters
                'post_type'         => 'cpt-courses',
                'posts_per_page'    => -1,
                'order'             => 'ASC',
                'orderby'           => 'name',
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
                            <div id="center-link">
                                <i class="fa fa-link"></i>
                                <p>Learn More</p>
                            </div>
                        </a>
                            <div id="course-title"><a href='<?php the_permalink(); ?>'><?php the_title(); ?></a></div>
                        
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
        
        
        
        <?php get_sidebar( 'subsidiary' ); ?>
        
    </div><!-- #content -->
    
    
<?php get_footer(); ?>