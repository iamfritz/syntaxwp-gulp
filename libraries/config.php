<?php
#fritz
#wp_set_password('!p@$$w0rd',1);
#wp_set_password('straight@PASS',1);

/*add ?debug=1 to url to enable error and must be admin*/
if(isset($_GET['debug']) && $_GET['debug']) {
  if ( current_user_can( 'administrator' ) ) {
      #define('WP_DEBUG_DISPLAY', true);
      @ini_set('display_errors', 1);
  }
}

#declare contant variable here
define( 'URI_ASSETS', get_bloginfo('template_directory').'/assets/' );
define( 'ASSETS_IMG', get_bloginfo('template_directory').'/assets/images/' );
define( 'ASSETS_JS', get_bloginfo('template_directory').'/assets/js/' );
define( 'ASSETS_CSS', get_bloginfo('template_directory').'/assets/css/' );
define( 'ASSETS_PLUGINS', get_bloginfo('template_directory').'/assets/plugins/' );
define( 'URI_TEMPLATES', get_bloginfo('template_directory').'/templates/' );

define( 'DEFAULT_IMAGE', ASSETS_IMG.'no-image.png');

define( 'DISABLE_SEARCH', false );

/*ACF SETTINGS */
define( 'HIDE_ADMIN_BAR', true );
define( 'HIDE_ACF', false );
define( 'LOAD_ACF_THEME_PLUGIN', false );
define( 'DEFAULT_ACF_FIELDS', false );

define( 'ACF_GOOGLEMAP_API', 'AIzaSyArbT0wb27lSMtjUhgdQlETq7mk-v24kW4' );
/*ACF SETTINGS END */

/* settings */

/*
 add additional js and css
 */
$css_array = array(
			//'s-app' 			=> ASSETS_CSS. 'app.css'
			);
$js_array = array(
			//'s-app' 		    => ASSETS_JS. 'app.js'
			);

define( 'css_array', serialize($css_array) );
define( 'js_array', serialize($js_array) );

define( 'image_sizes', serialize(
  array(
    array( 'post_image', '168', '168'  ),
    array( 'page_image', '270', '250'  )    
  )
) );
