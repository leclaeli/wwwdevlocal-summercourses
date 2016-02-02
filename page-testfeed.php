<?php
/**
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

<?php 
	$rss = fetch_feed( 'https://uwm.edu/budget/feed/?cat=20,8' );
	// var_dump($rss);

$maxitems = 0;

if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

    // Figure out how many total items there are, but limit it to 5. 
    $maxitems = $rss->get_item_quantity( 10 ); 

    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items( 0, $maxitems );

endif;
?>

<?php $json = file_get_contents('http://www5.uwm.edu/news/api/info'); ?>

<?php 
    $json_a=json_decode($json,true); 
    $json_o=json_decode($json);
    // echo $json_o->posts[0]->title;
?>
<?php // echo '<pre>'; print_r($json_o); echo '</pre>'; ?>


<ul>
    <?php if ( $maxitems == 0 ) : ?>
        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
    <?php else : ?>
        <?php // Loop through each feed item and display each item as a hyperlink. ?>
        <?php foreach ( $rss_items as $item ) : ?>
            <li>
                <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                    title="<?php printf( __( 'Posted %s', 'my-text-domain' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?php echo esc_html( $item->get_title() ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

		</div><!-- #primary -->
		
		<?php get_sidebar( 'primary' ); ?>
		<?php get_sidebar( 'subsidiary' ); ?>
		
	</div><!-- #content -->

<?php get_footer(); ?>