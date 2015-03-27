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

add_action("gform_after_submission_1", "create_course_from_submission", 10, 2);
function create_course_from_submission($entry, $form) {
	//First need to create the post in its basic form
	$new_course = array(
		'post_title' => ($entry[1]),
		'post_status' => 'draft',
		'post_date' => date('Y-m-d H:i:s'),
		'post_type' => 'cpt-courses'
	);
	//From creating it, we now have its ID
	$post_id = wp_insert_post($new_course);
	//Now we add the meta
	//$thePrefix = '_sitemeta_';
	// update_post_meta($theId, $thePrefix.'colour', ucwords($entry[1]));
	// update_post_meta($theId, $thePrefix.'personality', ucwords($entry[2]));
	// update_post_meta($theId, $thePrefix.'smell', ucwords($entry[3]));
	update_field('field_54bfbf110f93c', $entry['2'], $post_id); // Course # and Section
	update_field('field_54bfbf9c0f93d', $entry['7'], $post_id); // Class #
	update_field('field_54bfbff20f93e', $entry['6'], $post_id); // Number of credits
	update_field('field_54bfc01c0f93f', $entry['4'], $post_id); // Instructor
	update_field('field_54f0de30ac21e', $entry['5'], $post_id); // Start date
	update_field('field_54f0de54ac21f', $entry['8'], $post_id); // End date
	// Update post 37
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
		//$gf_dir_end = strrpos($gf_dir_begin, '/');
		//$gf_dir = substr($gf_dir_begin, 0, $gf_dir_end);

		$filename = $upload_dir['basedir'] . '/' . $gf_dir_begin;

		// foreach ($_FILES as $uploaded_file => $value) {
		// 	//print_r( $value['name'] );
		// 	$filename = $upload_dir['basedir'] . '/' . $gf_dir . '/' . $value['name'];
		// }

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
	// echo '<pre>'; print_r($entry); echo '</pre>';
	if(!empty($entry['10'])) {
		$file_url = $entry['10']; //great but what is its url?
		$upload_dir = wp_upload_dir(); // where is WordPress putting uploads?
		$gf_dir_begin = stristr($file_url, "gravity");
		$filename = $upload_dir['basedir'] . '/' . $gf_dir_begin;
		// The ID of the post this attachment is for.
		//$post_id = $post_id;
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


// add_action("gform_after_submission_1", "attach_pdf_to_post", 10, 2);
// function attach_pdf_to_post($entry, $form) {
	
// }
    
    // // get the last image added to the post
    // $attachments = get_posts(array('numberposts' => '1', 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));
    
    // if(sizeof($attachments) == 0)
    //     return; //no images attached to the post

    // // set image as the post thumbnail
    // set_post_thumbnail($post_id, $attachments[0]->ID);
	//set_post_thumbnail( $post_id, $entry['9'] );



/* show plugins */
// function showPlugins() {
// 	// Check if get_plugins() function exists. This is required on the front end of the
// 	// site, since it is in a file that is normally only loaded in the admin.
// 	if ( ! function_exists( 'get_plugins' ) ) {
// 		require_once ABSPATH . 'wp-admin/includes/plugin.php';
// 	}

// 	$all_plugins = get_plugins();
// 	$all_plugins_keys = array_keys($all_plugins);

// 	$loopCtr = 0;
// 	echo "<table>";
// 	foreach ($all_plugins as $plugin_item) {

// 	     // Get our Plugin data variables
// 	     $plugin_root_file   = $all_plugins_keys[$loopCtr];
// 	     $plugin_title       = $plugin_item['Title'];
// 	     $plugin_version     = $plugin_item['Version'];
// 	     $plugin_status      = is_plugin_active($plugin_root_file) ? 'active' : 'inactive';
// 		echo '<tr><td>' .$plugin_title . '</td><td>' . $plugin_version . '</td><td>' . $plugin_status .'</td></tr>';
// 		$loopCtr++;
// 	}
// 	echo "</table>";
// }
