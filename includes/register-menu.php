<?php
/**
 * Register nav menus
 */
function wpz_wp_register_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary' ),
		)
	);
}
add_action( 'init', 'wpz_wp_register_menus' );