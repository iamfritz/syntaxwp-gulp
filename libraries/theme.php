<?php
//initial themes
if ( ! function_exists( 'init_theme_support' ) ) {
    /* Sets up theme defaults and registers support for various WordPress features. */
    function init_theme_support() {

      // This theme uses wp_nav_menu() in one location.
      register_nav_menus( array(
          'primary'   => __( 'Primary Menu', 'sac-themes' ),
          'footer'    => __( 'Footer Menu', 'sac-themes' )
      ) );

      /* Switch default core markup for search form, comment form, and comments to output valid HTML5. */
      add_theme_support( 'html5', array(
          'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
      ) );

      /* Enable support for Post Formats. */
      add_theme_support( 'post-formats', array(
          'image', 'video'
      ) );

      add_theme_support( 'custom-header',
        array(
                  'width'         => 364,
                  'height'        => 200,
                  'default-image' => get_template_directory_uri() . '/assets/images/logo.png',
                  'uploads'       => true,
              )    
      );

      add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

      /* WordPress manage the document title. */
      add_theme_support( 'title-tag' );

      /* Enable support for Post Thumbnails on posts and pages. */
      add_theme_support( 'post-thumbnails' );

      /* Creates and registers a new post thumbnails size */
      $sizes = unserialize( image_sizes );
      foreach ($sizes as $key => $size) {
        add_image_size( $size[0], $size[1], $size[2], array( 'center', 'center' )  );
      }

      add_post_type_support('page', 'excerpt');       # enable excerpt in page
      add_filter('widget_text', 'do_shortcode');      # enable shortcode in text widgets
      add_filter('excerpt_length', function($length) {
          return 150;
      });

      /************** SEARCH **************/
      # webrewrite.com/url-rewriting-htaccess/
      # https://adambalee.com/search-wordpress-by-custom-fields-without-a-plugin/
      if(DISABLE_SEARCH) {
        add_action( 'parse_query', 'fb_filter_query' );
        add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
      }

    }
    add_action( 'after_setup_theme', 'init_theme_support' );

} // init_theme_support

/* hide admin bar to none admin */
if (HIDE_ADMIN_BAR) {
  if (!current_user_can('administrator')) :
        show_admin_bar(false);
  endif;
}

function custom_login_logo() {
    echo '<style type="text/css">
        h1 a { 
        background-image:url('.URI_ASSETS.'images/logo-admin.png) !important;
        background-size: 240px auto !important;
        height: 80px !important;
        width: 100% !important; 	 
    	}
    </style>';
}
add_action('login_head', 'custom_login_logo');

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
  return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function annointed_admin_bar_remove() {
  global $wp_admin_bar;

  /* Remove their stuff */
  $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);

function hide_update_notice_to_all_but_admin_users(){
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );


// replace WordPress Howdy in WordPress 3.3
function replace_howdy( $wp_admin_bar ) {
    $my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', 'Logged in as', $my_account->title );            
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );
}
add_filter( 'admin_bar_menu', 'replace_howdy',25 );

function no_wp_logo_admin_bar_remove() {
    ?>
        <style type="text/css">
          #wpadminbar #wp-admin-bar-site-name > .ab-item::before {
            content: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.png') !important; 
            transform: scale(.6);     
            top: -12px;      
            max-width: 52px;  
            max-height: 32px;  
          }
        </style>
    <?php
}
#add_action('wp_before_admin_bar_render', 'no_wp_logo_admin_bar_remove', 0);

/*******THEME default hook END***********************************************************/











