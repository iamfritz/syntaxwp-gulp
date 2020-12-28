<?php
/* Registers the widget area. */

/**
* 
*/

class sac_sidebar
{
	
	function __construct()
	{
		add_action( 'widgets_init', array( $this, 'register' ) );
	}    

	function register() {

			/* -------- Custom Sidebar Registrations -------- */
			$sidebars_array = array(
				'blog'				=> array(
											'name'          => 'Blog Widgets',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>'
										),				
				'footer'				=> array(
											'name'          => 'Footer',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="col-sm-4 widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>',
										),
				'about'					=> array(
											'name'          => 'About',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>',
										),	
				'page'					=> array(
											'name'          => 'Page',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>',
										),					
				'business'					=> array(
											'name'          => 'Business',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>',
										),
				'business-ads'					=> array(
											'name'          => 'Business Ads',
											//'id'            => $sidebar_key,
											//'description'   => 'dynamic_sidebar("'.$sidebar_key.'")',
											'before_widget' => '<div id="%1$s" class="widgets %2$s">',
											'after_widget'  => '</div>',
											'before_title'  => '<h3 class="widget_title">',
											'after_title'   => '</h3>',
										)																													
				// 'footer-three'			=> 'Footer Three',
				//'footer-four'			=> 'Footer Four'
			);

			foreach ($sidebars_array as $sidebar_key => $sidebar_value) {

				$args = $sidebar_value;
				$args['id']            = $sidebar_key;
				$args['description']   = 'dynamic_sidebar("'.$sidebar_key.'")';
				register_sidebar( $args);

			}


	} //register


}

new sac_sidebar();

?>