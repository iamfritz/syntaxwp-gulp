<?php
/**
 * Script functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

/**
 * Enqueue theme scripts
 */
function wpx_wp_theme_assets() {

	/**
	 * Set a script handle prefix based on theme name.
	 * Note that this should be the same as the `themePrefix` var set in your Gulpfile.js.
	 */
	$theme_handle_prefix = 'syntaxified';
	$theme_handle_prefix = 'app';

	/**
	 * Enqueue common scripts.
	 */
	wp_enqueue_script( $theme_handle_prefix . '-scripts', get_template_directory_uri() . '/assets/js/' . $theme_handle_prefix . '.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_style( $theme_handle_prefix . '-styles', get_template_directory_uri() . '/assets/css/' . $theme_handle_prefix .'.css', array(), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'wpx_wp_theme_assets' );
