<?php
#fritz
#wp_set_password('!p@$$w0rd',1);

/*add ?debug=1 to url to enable error and must be admin*/
if($_GET['debug']) {
  #if ( current_user_can( 'administrator' ) ) {
      define('WP_DEBUG_DISPLAY', true);
      @ini_set('display_errors', 1);
  #}
}

/* Includes Libraries */
require get_template_directory() . '/libraries/config.php';
include get_template_directory() . '/libraries/model/help.class.php';
require get_template_directory() . '/libraries/enqueue-assets.php';
require get_template_directory() . '/libraries/register-sidebar.php';
require get_template_directory() . '/libraries/register-post-types.php';

/*custom hook*/
require get_template_directory() . '/libraries/custom-hooks.php';
/*add custom shortcodes*/
require get_template_directory() . '/libraries/shortcodes.php';
/* create theme custom widgets*/
require get_template_directory() . '/libraries/custom-widgets.php';

require get_template_directory() . '/libraries/template-tags.php';

include get_template_directory() . '/libraries/bootstrap.php';

/* customize users*/
#require get_template_directory() . '/libraries/user-login.php';
#require get_template_directory() . '/libraries/user-fields.php';

/*auto add ACF or plugins to the themes*/
require get_template_directory() . '/libraries/plugins.php';

/* disable updates */
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
/* disable updates */

/*
	strictly no other code here add hook/additional codes in include files
	add custom code in custom-hook.php
	for more info GMG
*/
?>