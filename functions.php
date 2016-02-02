<?php
/**
 * shoe-horning custom_excerpt length and such into child theme
 */
add_action( 'after_setup_theme', 'my_child_theme_setup' );

function my_child_theme_setup() {
	//customize excerpt length
	function wtmj_excerpt_length( $length ) {
		return 22;
	}
	add_filter( 'excerpt_length', 'wtmj_excerpt_length', 999 );
	
	//Replace the_excerpt functions ending of '[…]' with something else
	function wtmj_excerpt_more( $more ) {
		return '...';
	}
	add_filter('excerpt_more', 'wtmj_excerpt_more');		
}

/**
 * Add custom image sizes.
 */
add_image_size( 'home_thumb', 230, 230, array('center','center') ); // (cropped)
add_image_size( 'home_featured_img', 650, 400, array('center','top') ); // (cropped)

function rotate_resize( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
	if( false )
		return $payload;
	if ( $crop ) {
		// $min_w = min($dest_w, $orig_w);
		// $min_h = min($dest_h, $orig_h);
			if ($dest_w === 650 ) {
				//	print_r($orig_w);
				$src_h = $orig_w / 1.625;
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
	wp_enqueue_style( 'bootstrap-grid', get_stylesheet_directory_uri() . '/css/bootstrap-grid.css' );
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
			echo '<h3>' . get_the_title() . '</h3>'; ?>
			<!-- <p><?php the_field( 'course_number_section' ); ?></p> -->
			<?php
			// echo '<p>' . get_the_content() . '</p>';
			// echo '<ul><li><b>Class Number: </b>'; 
			// the_field( 'class_number' );
			// echo '</li><li><b>Credits: </b>'; 
			// the_field( 'number_of_credits' );
			// echo '</li><li><b>Instructor: </b>'; 
			// the_field( 'instructor' );
			// echo '</li></ul>';
			the_excerpt();
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

/*
** Create Custom Post from Gravity Form
*/

add_action("gform_after_submission_2", "create_course_from_submission", 10, 2);
function create_course_from_submission($entry, $form) {
	//First need to create the post in its basic form
	$year_id = get_cat_id( '2016' ); // get the category id of the current year
	$new_course = array(
		'post_title' => ($entry[1]),
		'post_status' => 'draft',
		'post_date' => date('Y-m-d H:i:s'),
		'post_type' => 'cpt-courses',
		'post_category' => array( $year_id ),
	);
	//From creating it, we now have its ID
	$post_id = wp_insert_post($new_course);
	//Now we add the meta / advanced custom fields
	update_field('field_54bfbf110f93c', $entry['2'], $post_id); // Course # and Section
	update_field('field_54bfbf9c0f93d', $entry['7'], $post_id); // Class #
	update_field('field_54bfbff20f93e', $entry['6'], $post_id); // Number of credits
	update_field('field_54bfc01c0f93f', $entry['4'], $post_id); // Instructor
	update_field('field_56952f9cec6d1', $entry['12'], $post_id); // UWM Email
	update_field('field_569418de00e9d', $entry['14'], $post_id); // Course Dates
	$start_end_dates = explode(' ', $entry['14'] );
	update_field('field_54f0de30ac21e', $start_end_dates[0], $post_id); // Start date - needs YYYYMMDD format
	update_field('field_54f0de54ac21f', $start_end_dates[1], $post_id); // End date - needs YYYYMMDD format
	update_field('field_569417de6c0ca', $entry['15'], $post_id); // Course Level
	update_field('field_56b0ec5b4ce0b', array($entry['16.1'], $entry['16.2'], $entry['16.3'], $entry['16.4'], $entry['16.5']), $post_id); // Meets Requirements
	// Update post
	$my_post = array(
	  'ID'           => $post_id,
	  'post_content' => $entry['11']
	);
	// Update the post into the database
	wp_update_post( $my_post );

	if(!empty($entry['9'])) {
		// $filename should be the path (not url) to a file in the upload directory.
		$file_url = $entry['9']; //great but what is its url?
		$upload_dir = wp_upload_dir(); // where is WordPress putting uploads?
		//$base_name = basename($file_url); // name of the file
		$gf_dir_begin = stristr($file_url, "gravity");

		$filename = $upload_dir['basedir'] . '/' . $gf_dir_begin;


		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// set attachment as featured image
		set_post_thumbnail( $post_id, $attach_id);
	}

	//attach_pdf_to_post();
	if(!empty($entry['10'])) {
		$file_url = $entry['10']; //great but what is its url?
		$upload_dir = wp_upload_dir(); // where is WordPress putting uploads?
		$gf_dir_begin = stristr($file_url, "gravity");
		$filename = $upload_dir['basedir'] . '/' . $gf_dir_begin;
		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );
		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		// require_once( ABSPATH . 'wp-admin/includes/file.php' );
		// require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		update_field('field_54f0eda5a8693', $attach_id, $post_id); // Course Syllabus
	}
}

function send_email() {
	// global $post_type;
	// die( $post_type );
	// if ( "cpt-courses" == $post_type ) {

		$uwm_email = get_field( 'uwm_email' );
		$to = $uwm_email;
		$subject = "Course Published - UWM Summer Online Courses Website";
		$body = "<p>Your course, [course title], is now available to view at [URL]. Please review this page for any inaccuracies and email Dylan Barth at djbarth@uwm.edu with corrections or questions.";
		if (mail($to, $subject, $body)) {
		echo("<p>Message successfully sent!</p>");
		} else {
		echo("<p>Message delivery failed...</p>");
		}
	// }
}

add_action('publish_cpt-courses','send_email');
