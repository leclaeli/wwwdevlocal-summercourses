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
                                    echo '<h3>' . get_the_title() . '</h3>';
                                    if ( has_category( 'class-full' ) ) {
                                        echo '<div class="class-full alert">';
                                        echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a><span>Class is full</span></div>';
                                    }
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
       
        <div class="col-960">
            <h2 class="browse">Browse 2016 Featured Courses</h2>
        </div>
        
        <div id="courses-facets" class="container">

            <!-- <h3>Search & Filter</h3> -->

            <div class="row">

                <div class="col-sm-6 center-vertically">
                    <div class="col-sm-9">
                        <?php echo facetwp_display( 'facet', 'search_facet' ); ?>
                    </div>
                    <div class="col-sm-3">
                        <button onclick="FWP.reset()" class="click-reset-facets reset-facets">Reset</button>
                    </div>
                </div>

                <div class="col-sm-6">
                    <span class="filters-dropdown">Filters</span>
                </div>

            </div>

            <div class="row hidden">

                <div class="col-sm-4 filter-container">
                    <h4>Course Level</h4>
                    <?php echo facetwp_display( 'facet', 'facet_course_level' ); ?>
                </div>

                <div class="col-sm-4 filter-container">
                    <h4>Dates</h4>
                    <?php echo facetwp_display( 'facet', 'date_facet' ); ?>
                </div>

                <div class="col-sm-4 filter-container">
                    <h4>Meets Requirements</h4>
                    <?php echo facetwp_display( 'facet', 'requirements_facet' ); ?>
                </div>

            </div>

        </div>

        <div id="courses-content">
            <?php echo facetwp_display( 'template', 'courses_filter' ); ?>
        </div>
        
        <div id="full-courses">
            <?php
                $fc_cat_id = get_cat_id( 'class-full' );
                $fc_args = array(
                    'post_type' => 'cpt-courses',
                    'category_name' =>'2016, class-full',
                    // 'category__in' => array( $fc_cat_id, '2016' ),
                );
                $full_courses = get_posts( $fc_args );
                $c = 0; 
            ?>
            <h2 class="browse">Full Courses</h2>
            <div id="courses">
                <?php foreach ($full_courses as $post ) : setup_postdata( $post );
                    if ( $c == 4 ) {
                        $c = 0;
                    }
                    $c++;
                    if ( $c == 1 ) {
                        $col_class = "first-of-four";
                    } else if ( $c == 4 ) {
                        $col_class = "last-of-four";
                    } else {
                        $col_class = "middle";
                    }
                ?> 
                <li id="<?php the_ID(); ?>" class="<?php echo $col_class; ?>">
                    <?php 
                        // $start_date = strtotime( get_field( 'start_date' ) ); 
                        // $cutoff_date = strtotime('+ 1 week');
                        // $current_date = date('m/d/Y');
                        // echo $start_date;
                        // echo date_format( $start_date, 'U');
                        // echo $cutoff_date;
                    ?>
                    <?php //if ( $start_date > $cutoff_date ) : ?>
                        <!-- <div class="unavailable">Registration Closed: <?php //echo $start_date . ' ' . $current_date; ?></div> -->
                    <?php //endif; ?>
                     
                    <a href='<?php the_permalink(); ?>'>
                        <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                            the_post_thumbnail('home_thumb'); 
                        } else {  ?>
                            <div style="width: 222px; height: 222px; background-color: #ccc;"></div>
                        <?php } ?>
                        <div id="center-link">
                            <i class="fa fa-link"></i>
                            <p>Learn More</p>
                        </div>
                    </a>
                    <div id="course-title"><a href='<?php the_permalink(); ?>'><?php the_title(); ?></a></div>
                </li>
                <?php endforeach;
                wp_reset_postdata(); ?>
            </div> 
        </div>

        <div class="loading">
            <i class="fa fa-spinner fa-spin fa-3x"></i>
            <div>Loading Courses</div>
        </div>

        <div class="done-loading">
            <span>All courses have been loaded. To narrow your results use the <a class="click-reset-facets" href="#courses-facets">search and filter bar</a>.</span>
        </div>

    </div>
    
    <div id="content" class="content-area">
        <?php get_sidebar( 'subsidiary' ); ?> 
    </div><!-- #content -->
    
<?php get_footer(); ?>