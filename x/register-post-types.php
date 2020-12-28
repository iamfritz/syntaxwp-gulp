<?php

/**
* 
*/
class sac_post_type
{
	var $flush = false;

	function __construct($flush = false)
	{
		$this->flush = $flush;

		add_action( 'init', array($this, 'register') );
	}

	function register() {
		
		$post_type = array();

/*		$singular 	= 'Testimonial';
		$plural 	= 'Testimonials';
		$labels = array(
			'name'                => __( $plural, 'sac-themes' ),
			'singular_name'       => __( $singular, 'sac-themes' ),
			'add_new'             => __( 'Add New '.$singular, 'sac-themes', 'sac-themes' ),
			'add_new_item'        => __( 'Add New '.$singular, 'sac-themes' ),
			'edit_item'           => __( 'Edit '.$singular, 'sac-themes' ),
			'new_item'            => __( 'New '.$singular, 'sac-themes' ),
			'view_item'           => __( 'View '.$singular, 'sac-themes' ),
			'search_items'        => __( 'Search '.$singular, 'sac-themes' ),
			'not_found'           => __( 'No '.$singular.' found', 'sac-themes' ),
			'not_found_in_trash'  => __( 'No '.$singular.' found in Trash', 'sac-themes' ),
			'parent_item_colon'   => __( 'Parent '.$singular.':', 'getcre8ive-2	015' ),
			'menu_name'           => __( $singular, 'sac-themes' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => TRUE,
			'description'         => 'description',
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-format-status',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'supports'            => array(
									'title', 'editor', 'revisions'
									)
		);

		$post_type[] = array( 'post' => strtolower($plural), 'args' => $args );*/

		$singular 	= 'Business';
		$plural 	= 'Business';
		$labels = array(
			'name'                => __( $plural, 'sac-themes' ),
			'singular_name'       => __( $singular, 'sac-themes' ),
			'add_new'             => __( 'Add New '.$singular, 'sac-themes', 'sac-themes' ),
			'add_new_item'        => __( 'Add New '.$singular, 'sac-themes' ),
			'edit_item'           => __( 'Edit '.$singular, 'sac-themes' ),
			'new_item'            => __( 'New '.$singular, 'sac-themes' ),
			'view_item'           => __( 'View '.$singular, 'sac-themes' ),
			'search_items'        => __( 'Search '.$singular, 'sac-themes' ),
			'not_found'           => __( 'No '.$singular.' found', 'sac-themes' ),
			'not_found_in_trash'  => __( 'No '.$singular.' found in Trash', 'sac-themes' ),
			'parent_item_colon'   => __( 'Parent '.$singular.':', 'getcre8ive-2	015' ),
			'menu_name'           => __( $singular, 'sac-themes' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => TRUE,
			'description'         => 'description',
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 21,
			'menu_icon'           => ASSETS_IMG . 'chart-search-icon.png',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array(
									'title', 'editor', 'revisions', 'thumbnail', 'excerpt', 'author', 'comments'
									)
		);
		$post_type[] = array( 'post' => strtolower($plural), 'args' => $args );

		foreach($post_type as $row) {
			register_post_type( $row['post'], $row['args'] );
		}


	    register_taxonomy(  
	        'business-type',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
	        'business',        //post type name
	        array(  
	            'hierarchical' 	=> true,  
	            'label' 		=> 'Business Type',  //Display name
	            'query_var' 	=> true,
	            'rewrite' 		=> array(
	                'slug' 			=> 'cat', // This controls the base slug that will display before each term
	                'with_front' 	=> false // Don't display the category base before 
	            )
	        )  
	    ); 
		
		if($this->flush) {
			flush_rewrite_rules();
		}
	} //register

}

new sac_post_type(true);

?>
