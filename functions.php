<?php
add_image_size( 'home_thumb', 220, 220, array('center','center') ); // (cropped)
add_image_size( 'home_crop', 1800, 450, array('center','top') ); // (cropped)

function rotate_resize( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
	if( false )
		return $payload;
	if ( $crop ) {
		// $min_w = min($dest_w, $orig_w);
		// $min_h = min($dest_h, $orig_h);
			if ($dest_w === 1800 ) {
				//	print_r($orig_w);
				$src_h = $orig_w / 4;
				$src_y = ( $orig_h / 2 ) - ( $src_h / 2 );
				$dst_w = $dest_w;
				$dst_h = $dest_h;
				$src_w = $orig_w;
				$src_x = 0;
			} else {
				$aspect_ratio = $orig_w / $orig_h;
				$dst_w = min($dest_w, $orig_w);
				$dst_h = min($dest_h, $orig_h);

					if ( !$dst_w ) {
						$dst_w = intval($dst_h * $aspect_ratio);
					}

					if ( !$dst_h ) {
						$dst_h = intval($dst_w / $aspect_ratio);
					}

					$size_ratio = max($dst_w / $orig_w, $dst_h / $orig_h);

					$src_w = round($dst_w / $size_ratio);
					$src_h = round($dst_h / $size_ratio);

					if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
						$crop = array( 'center', 'center' );
					}

					list( $x, $y ) = $crop;
					if ( 'left' === $x ) {
					        $src_x = 0;
					} elseif ( 'right' === $x ) {
						$src_x = $orig_w - $src_w;
					} else {
						$src_x = floor( ( $orig_w - $src_w ) / 2 );
					}

					if ( 'top' === $y ) {
						$src_y = 0;
					} elseif ( 'bottom' === $y ) {
						$src_y = $orig_h - $src_h;
					} else {
						$src_y = floor( ( $orig_h - $src_h ) / 2 );
					}
				}
	} else {
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$src_w = $orig_w;
		$src_h = $orig_h;
		list( $dst_w, $dst_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		$src_y = 0;
		$src_x = 0;
	}
	// // imagecopyresampled ( 
	// 	resource $dst_image ,
	// 	resource $src_image , 
	// 	int $dst_x , 1
	// 	int $dst_y , 2
	// 	int $src_x , 3 s_x
	// 	int $src_y , 4 s_y
	// 	int $dst_w , 5
	// 	int $dst_h , 6
	// 	int $src_w , 7 crop_w
	// 	int $src_h   8 crop_h
	// 	)
	return array( 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
}

add_filter( 'image_resize_dimensions', 'rotate_resize', 10, 6 );



/* Enqueue Scripts/Styles */
function custom_js_script() {
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery'), false, false);
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-slide');
	// code to declare the URL to the file handling the AJAX request </p>
	wp_enqueue_script( 'ajax-script', get_stylesheet_directory_uri() . '/js/ajax-implementation.js', array( 'jquery' ) );
	wp_localize_script( 'ajax-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('wp_enqueue_scripts', 'custom_js_script');

/* Ajax Functions */
function MyAjaxFunction(){
	//get the data from ajax() call_user_func(function)
	$id = $_POST['id'];
	// The Query
	$course_args = array(
		'post_type' => 'cpt-courses',
		'p' => $id,
	);
	$the_query = new WP_Query( $course_args );
	// The Loop
	if ( $the_query->have_posts() ) {
		
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			echo '<h3>' . get_the_title() . '</h4>';
			// echo '<p>' . get_the_content() . '</p>';
			echo '<ul><li>';
			the_field( 'course_number_section' );
			echo '</li><li>'; 
			the_field( 'class_number' );
			echo '</li><li>'; 
			the_field( 'number_of_credits' );
			echo '</li><li>'; 
			the_field( 'instructor' );
			echo '</li></ul>';
		}
		
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	die;

	// $new_title = get_the_title( $id );
	// $course_info = get_post_field('post_content', $id);
	// $meta_values = get_post_meta( $id, 'instrument', true);
	// $results = "<h2>".$new_title."</h2>";
	// $results .= "<p>".$course_info."</p>";   
	// die($results);
}
	// creating Ajax call for WordPress
	 add_action( 'wp_ajax_nopriv_MyAjaxFunction', 'MyAjaxFunction' );
	 add_action( 'wp_ajax_MyAjaxFunction', 'MyAjaxFunction' );

/* Register Custom Post Types */
function codex_custom_posts_init() {
		$args_courses = array(
	      'public' => true,
	      'label' => 'Courses',
	      'rewrite' => array( 'slug' => 'courses' ),
	      'menu_icon' => 'dashicons-book',
	      'taxonomies' => array('post_tag', 'category'),
	      'has_archive' => true,
	      'supports' => array(
	            'title', 'editor', 'author', 'thumbnail',
	            'excerpt','custom-fields', 'revisions', 'page-attributes'
	            )
	    );
	register_post_type('cpt-courses', $args_courses);
}

add_action( 'init', 'codex_custom_posts_init' );


/* Add custom post types to taxonomy pages */
function add_custom_types_to_tax( $query ) {
    if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

        // Get all your post types
        $post_types = get_post_types();

        $query->set( 'post_type', $post_types );
        return $query;
    }
}
add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );

