<?php
//ACF

add_action('acf/init', 'wp_acf_op_init');
function wp_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

        // Add parent.
        $parent = acf_add_options_page(array(
            'page_title'  => __('Theme General Settings'),
            'menu_title'  => __('Theme Settings'),
            'redirect'    => false,
        ));

        // Add sub page.
        $child = acf_add_options_page(array(
            'page_title'  => __('Header Settings'),
            'menu_title'  => __('Header'),
            'parent_slug' => $parent['menu_slug'],
        ));

        $child = acf_add_options_page(array(
            'page_title'  => __('Footer Settings'),
            'menu_title'  => __('Footer'),
            'parent_slug' => $parent['menu_slug'],
        ));
        
        $child = acf_add_options_page(array(
            'page_title'  => __('Social Settings'),
            'menu_title'  => __('Social'),
            'parent_slug' => $parent['menu_slug'],
        ));

        $child = acf_add_options_page(array(
            'page_title'  => __('404 Settings'),
            'menu_title'  => __('404'),
            'parent_slug' => $parent['menu_slug'],
        ));

        
    }    
} //wp_acf_op_init

