<?php
/**
 * Register nav menus
 */
function st_wp_register_menus() {
	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'st-themes' ),
		'footer'    => __( 'Footer Menu', 'st-themes' )
	) );
}
add_action( 'init', 'st_wp_register_menus' );