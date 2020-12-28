<?php
/**
 * Admin functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

/**
 * Credit in admin footer
 */
function wpx_wp_admin_footer() {
	echo 'Developed by <a href="http://syntaxified.com">Syntaxified</a>';
}
add_filter( 'admin_footer_text', 'wpx_wp_admin_footer' );

/**
 * Change default greeting
 */
function wpx_wp_greeting( $wp_admin_bar ) {
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );

	if ( 0 !== $user_id ) {
		$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __( 'Hi, %1$s' ), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';

		$wp_admin_bar->add_menu(array(
			'id' => 'my-account',
			'parent' => 'top-secondary',
			'title' => $howdy . $avatar,
			'href' => $profile_url,
			'meta' => array(
				'class' => $class,
			),
		));
	}
}
add_action( 'admin_bar_menu', 'wpx_wp_greeting', 11 );


// ADD NEW COLUMN
function ST4_columns_head($defaults) {
    $defaults['wpb_track_post_views'] = 'View Count';
    return $defaults;
}
 
// SHOW THE FEATURED IMAGE
function ST4_columns_content($column_name, $post_ID) {

    if ($column_name == 'wpb_track_post_views') {
      $count = get_post_views_all($post_ID);
      $count = $count ? $count : 0;

      $today_view = get_post_views_today($post_ID);
      echo "$count total views, $today_view today views"; 
    }
}

function get_view_counts($post_ID){
    $count_key  = 'wpb_post_views_count';
    $count      = get_post_meta($post_ID, $count_key, true);  

    return $count;
}
#add_filter('manage_posts_columns', 'ST4_columns_head');
#add_action('manage_posts_custom_column', 'ST4_columns_content', 10, 2);

