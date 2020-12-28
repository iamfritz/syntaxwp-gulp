<?php

/**
* 
*/
class loadPostType
{
	var $flush = false;

	function __construct($flush = false)
	{
		$this->flush = $flush;

		add_action( 'init', array($this, 'register') );
	}

	function register() {
		
		$post_type = array();

		$singular 	= 'Testimonial';
		$plural 	= 'Testimonials';
		$labels = array(
			'name'                => __( $plural, 'st-themes' ),
			'singular_name'       => __( $singular, 'st-themes' ),
			'add_new'             => __( 'Add New '.$singular, 'st-themes', 'st-themes' ),
			'add_new_item'        => __( 'Add New '.$singular, 'st-themes' ),
			'edit_item'           => __( 'Edit '.$singular, 'st-themes' ),
			'new_item'            => __( 'New '.$singular, 'st-themes' ),
			'view_item'           => __( 'View '.$singular, 'st-themes' ),
			'search_items'        => __( 'Search '.$singular, 'st-themes' ),
			'not_found'           => __( 'No '.$singular.' found', 'st-themes' ),
			'not_found_in_trash'  => __( 'No '.$singular.' found in Trash', 'st-themes' ),
			'parent_item_colon'   => __( 'Parent '.$singular.':', 'st-themes' ),
			'menu_name'           => __( $singular, 'st-themes' ),
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

		$post_type[] = array( 'post' => strtolower($plural), 'args' => $args );

		foreach($post_type as $row) {
			register_post_type( $row['post'], $row['args'] );
		}


	    register_taxonomy(  
	        'testimonial-client',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
	        'testimonial',        //post type name
	        array(  
	            'hierarchical' 	=> true,  
	            'label' 		=> 'Testimonial Clients',  //Display name
	            'query_var' 	=> true,
	            'rewrite' 		=> array(
	                'slug' 			=> 't-client', // This controls the base slug that will display before each term
	                'with_front' 	=> false // Don't display the category base before 
	            )
	        )  
	    ); 
		
		if($this->flush) {
			flush_rewrite_rules();
		}
	} //register

}

new loadPostType(true);
