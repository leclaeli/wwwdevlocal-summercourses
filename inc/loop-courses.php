<?php $c = 0; ?>
<div id="courses">
    <?php while ( have_posts() ) {
        the_post();
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
    </li><?php
    } ?>
</div> 