<?php
/*******THEME default hook***********************************************************/
if ( ! function_exists( 'sac_setup' ) ) {
    /* Sets up theme defaults and registers support for various WordPress features. */
    function sac_setup() {

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

        /*$args = array(
            'width'         => 364,
            'height'        => 200,
            'default-image' => get_template_directory_uri() . '/assets/images/logo.png',
            'uploads'       => true,
        );
        add_theme_support( 'custom-header', $args );*/  

        add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

        /* WordPress manage the document title. */
        add_theme_support( 'title-tag' );

        /* Enable support for Post Thumbnails on posts and pages. */
        add_theme_support( 'post-thumbnails' );

        /* Creates and registers a new post thumbnails size */
        add_image_size( 'post_list', '168', '168', array( 'center', 'center' )  );
        add_image_size( 'news_thumbnail', '272', '266', array( 'center', 'center' )  );

        add_filter('widget_text', 'do_shortcode');      # enable shortcode in text widgets
        add_post_type_support('page', 'excerpt');       # enable excerpt in page

    }
} // sac_setup

add_action( 'after_setup_theme', 'sac_setup' );

if (!current_user_can('administrator')) :
      show_admin_bar(false);
endif;

#login header logo
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


function new_excerpt_length($length) {
    return 150;
}

add_filter('excerpt_length', 'new_excerpt_length');

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
          }

        </style>
    <?php
}
add_action('wp_before_admin_bar_render', 'no_wp_logo_admin_bar_remove', 0);
/*******CF7 hook***********************************************************/
#define('WPCF7_AUTOP', false );
/*add_action( 'wp_print_scripts', 'deregister_cf7_javascript', 100 );
function deregister_cf7_javascript() {
    if ( !is_page(array(8,10)) ) {
        wp_deregister_script( 'contact-form-7' );
    }
}
add_action( 'wp_print_styles', 'deregister_cf7_styles', 100 );
function deregister_cf7_styles() {
    if ( !is_page(array(8,10)) ) {
        wp_deregister_style( 'contact-form-7' );
    }
}*/
#add_filter( 'wpcf7_validate_text*', 'custom_filter_promo_code', 20, 2 );
 
function custom_filter_promo_code( $result, $tag ) {
    $current = WPCF7_ContactForm::get_current();
    $id = $current->id();

    if($id == 93) {

      $tag = new WPCF7_Shortcode( $tag );
      if ( 'customer-promocode' == $tag->name ) {
          $promocode = isset( $_POST['customer-promocode'] ) ? strtolower(trim( $_POST['customer-promocode'] )) : '';        
   
          if ( $promocode != 'tapas' ) {
              $result->invalidate( $tag, "Invalid Promo Code." );
          }
      }

    }
 
    return $result;
}

/*******CF7 hook END***********************************************************/

function fb_filter_query( $query, $error = true ) {
  
  if ( is_search() ) {
    $query->is_search = false;
    $query->query_vars[s] = false;
    $query->query[s] = false;
    
    // to error
    if ( $error == true )
      $query->is_404 = true;
  }
}

/************** SEARCH **************/
# webrewrite.com/url-rewriting-htaccess/
# https://adambalee.com/search-wordpress-by-custom-fields-without-a-plugin/
if(DISABLE_SEARCH) {
  add_action( 'parse_query', 'fb_filter_query' );
  add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
}


function prefix_limit_post_types_in_search( $query ) {
  if ( $query->is_search ) {
    if(isset($_GET['q']) && $_GET['q'] =='global') {
      $query->set( 'post_type', array( 'page', 'post' ) );
    } else {

      $query->set( 'post_type', array( 'business' ) );
      
      if(isset($_GET['c']) && $_GET['c']) {
          $category = $_GET['c'];
          $query->set( 'tax_query', array(
                                        array(
                                         'taxonomy'   => 'business-type',
                                         'field'      => 'slug', 
                                         'terms'      => $category
                                        ) 
                                      )
                      );
      }

      if(get_query_var( 's' ) == 'all') {
        $query->set( 's', '' );
      } else {
        $keywords = get_query_var( 's' );
        //savesearchactivity($keywords);
      }

      $meta_query             = array();
      #$meta_query['relation'] = 'OR';
      
      if(isset($_GET['loc']) && $_GET['loc']) {
          $fields = array('location_%_state');
          $input_text = $_GET['loc'];

          
          foreach($fields as $field) {
            $meta_query[] =   array(
                                   'key'   => $field,
                                   'value' => $input_text
                                  );
          }          

      }

      if(isset($_GET['postcode']) && $_GET['postcode']) {   
          $fields = array('location_%_postcode');       
          #'{$repeater_field_name}_%_{$sub_field_name}',
          $input_text = $_GET['postcode'];
          
          foreach($fields as $field) {
            $meta_query[] =   array(
                                   'key'   => $field,
                                   'value' => $input_text
                                  );
          }
      }  

      $meta_queries = array(
          'relation' => 'OR',
          array( 
              'key'=>'totalstarrate',
              'compare' => 'EXISTS'           
          ),
          array( 
              'key'=>'totalstarrate',
              'compare' => 'NOT EXISTS'           
          )
      );

      if($meta_query) {
        $meta_query[] = $meta_queries;
        $meta_queries = $meta_query;
      }

      $query->set('meta_query', $meta_queries);
      #$query->set('meta_key', 'totalstarrate');    
      $query->set('orderby', 'meta_value_num');    
      $query->set('order', 'DESC');    
      
    }
    #pr($query);
    #$query->set( 'showposts', 12);    
    
  }
  return $query;
}
add_filter( 'pre_get_posts', 'prefix_limit_post_types_in_search' );

// custom filter to replace '=' with 'LIKE'
function my_posts_where( $where ) {
    
    $where = str_replace("meta_key = 'location_%", "meta_key LIKE 'location_%", $where);

    return $where;
} 
add_filter('posts_where', 'my_posts_where');

function savesearchactivity() {

} //savesearchactivity

function search_url_rewrite_rule() {
  if(is_search() && isset( $_GET['view'] )) {
    $_SESSION['search_view'] = $_GET['view'];    
  }
  if ( is_search() && isset( $_GET['s'] ) ) {

    $params = array();
    if(isset($_GET['q']) && $_GET['q'] =='global') {
      $params['q'] = $_GET['q'];
    } else {
      if(isset($_GET['c']) && $_GET['c']) {
        $params['c'] = $_GET['c'];
      }
      if(isset($_GET['loc']) && $_GET['loc']) {
        $params['loc'] = $_GET['loc'];
      } 
      if(isset($_GET['postcode']) && $_GET['postcode']) {
        $params['postcode'] = $_GET['postcode'];
      }             
    }    
   
    $f_query = $params ? '?'.http_build_query($params) : '';
    
    $s    = get_query_var( 's' ) ? get_query_var( 's' ) : 'all';
    $url  = home_url( "/search/" ) . urlencode( $s )."$f_query";    
    wp_redirect( $url );
    exit();
  } 
}
add_action( 'template_redirect', 'search_url_rewrite_rule' );

/************** SEARCH **************/


/*******THEME default hook END***********************************************************/

#
/*******Other hook start here***********************************************************/

/*******POST VIEW***********************************************************/
function wpb_set_post_views_date($post_id) {
    
    $user_id    = get_current_user_id() ? get_current_user_id() : 'visitor';
    $today      = strtotime(date("Y_M_d"));
    $today      = date("Y_M_d");
    $count_key  = 'wpb_post_views_date';

    $views      = get_post_meta($post_id, $count_key, true);
    if($views == '') {
      $date_views = array( $today => 1);
      $date_views = json_encode($date_views);

      delete_post_meta($post_id, $count_key);
      add_post_meta($post_id, $count_key, $date_views);
    
    } else {

      $date_views   = (array) json_decode($views);
      $session_key  = 'wpb_post_views_date-'.$today. '_' .$user_id. '_' .$post_id;
      
      if(!isset($_SESSION[$session_key])){
        $_SESSION[$session_key] = $post_id;

        if (array_key_exists($today, $date_views)){
          $date_views[$today] = $date_views[$today] + 1;
        } else {
          $date_views[$today] = 1;
        }
      
        $date_views = json_encode($date_views);

        update_post_meta($post_id, $count_key, $date_views);
        wpb_update_post_views($post_id);
      }        
    }
} //wpb_set_post_views_date

function wpb_update_post_views($postID) {
    
    $count = get_post_views_date($postID);
    update_post_meta($postID, $count_key, $count);
}

function get_post_views_date($postID){
    $count_key  = 'wpb_post_views_date';

    return get_post_meta($postID, $count_key, true);
} //get_post_views_date

function get_post_views_all($postID) {
  $views = get_post_views_date($postID);
  $count_key = 'wpb_post_views_count';
  
  if($views) {
    $date_views   = (array) json_decode($views);
    $count = 0;
    foreach ($date_views as $key => $value) {
      $count = $count + $value;
    }
  } else {
    $count = 1;
  }
  return $count;
} //get_post_views_all

function get_post_views_today($postID){
    $post_views     = get_post_views_date($postID);
    $post_views     = $post_views ? (array) json_decode($post_views) : array();
    $today          = date("Y_M_d");

    return (array_key_exists($today, $post_views)) ? $post_views[$today] : 0;
} //get_post_views_date

function searchlog_keywords(){
  global $wp_query, $query_string; 
  $post_type = get_query_var( 'post_type' );
  $keyword    = get_query_var( 's' );
  if(in_array('business', $post_type) && $keyword) {

      query_posts($query_string . '&posts_per_page=-1');

      $post_count = $wp_query->found_posts;
      $user_id    = get_current_user_id() ? get_current_user_id() : 'visitor';

      $search = array();
      $keys                   = sanitize_title($keyword);
      $today                  = date("Y_M_d");
      $search['keyword']      = $keyword;
      $search['found_posts']  = $post_count;
      $search['count']        = 1;
      $search['last_ip']   = $_SERVER['REMOTE_ADDR'];
      $search['user']         = $user_id;
      
      //$search['post_ids']   = implode(',',$post_ids);
      
      $newlog = array();
      $newlog[$keys][$today] = $search;
      #pr($newlog);
      $post_ids = array();
      // The Loop
      if ( have_posts() ) {
                    
          while ( have_posts() ) { the_post(); 
              #$post_ids[] = get_the_ID();     
              $post_id = get_the_ID();
              $count_key = 'post_search_log';
              $searchlog = get_post_meta($post_id, $count_key, true);
              
              #echo "$post_id $searchlog <br />";
              if($searchlog == '') {
                $newlog = json_encode($newlog);

                #delete_post_meta($post_id, $count_key);
                add_post_meta($post_id, $count_key, $newlog);
              
              } else {
                  $searchlog_array = array();

                  $searchlog   = json_decode($searchlog, true);
                  
                  $keyword_key  = sanitize_title($keyword);
                  $session_key  = 'post_search_log-'.$today. '_' .$keyword_key. '_' .$user_id. '_' .$post_id;
                  
                  if(!isset($_SESSION[$session_key])) {
                      $_SESSION[$session_key] = $keyword;                      
                      if (array_key_exists($keys, $searchlog)){
                          $row_log = $searchlog[$keys];
                          
                          if (array_key_exists($today, $row_log)){
                              $searchlog[$keys][$today]['count'] = $row_log[$today]['count'] + $search['count'];
                          } else {
                              $searchlog[$keys][$today] = $search;
                          }
                          //wpb_update_post_views($post_id);
                      } else {
                        $searchlog[$keys][$today] = $search;
                      }        
                      
                      $searchlog = json_encode($searchlog);                      
                      update_post_meta($post_id, $count_key, $searchlog);
                }
              } 
          } // end while                                                                                                                         
      } // end if loop

      // Restore original Post Data
      wp_reset_postdata();
      wp_reset_query();  
  }
} //searchlog_keywords

function wpb_set_post_views($postID) {
    
  $count_key = 'wpb_post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count==''){
      $count = 0;
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, '0');
  } else {
      if(!isset($_SESSION['wpb_post_views_count-'. $postID])){
        $_SESSION['wpb_post_views_count-'. $postID] = $postID;
        $count++; 
        update_post_meta($postID, $count_key, $count);
      }        
  }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
# add to header   wpb_set_post_views(get_the_ID());
#auto add
/*function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    wpb_set_post_views($post_id);    
}
add_action( 'wp_head', 'wpb_track_post_views');*/
add_action( 'wp_head', 'wpb_set_post_views_date');

// ADD NEW COLUMN
function ST4_columns_head($defaults) {
    $defaults['wpb_track_post_views'] = 'View Count';
    return $defaults;
}
 
// SHOW THE FEATURED IMAGE
function ST4_columns_content($column_name, $post_ID) {

    if ($column_name == 'wpb_track_post_views') {
      $count = get_post_views_all($post_ID);
      $count = $count ? $count : 0;

      $today_view = get_post_views_today($post_ID);
      echo "$count total views, $today_view today views"; 
    }
}

function get_view_counts($post_ID){
    $count_key  = 'wpb_post_views_count';
    $count      = get_post_meta($post_ID, $count_key, true);  

    return $count;
}
add_filter('manage_posts_columns', 'ST4_columns_head');
add_action('manage_posts_custom_column', 'ST4_columns_content', 10, 2);

add_filter('manage_business_posts_columns', 'ST4_columns_head');
add_action('manage_business_posts_custom_column', 'ST4_columns_content', 10, 3);

function set_view_on_searched($post_ID, $keyword){
    
    $count_key  = 'wpb_post_view_on_searched';
    $count      = get_post_meta($post_ID, $count_key, true);  
    
    if($count == ''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        if(!isset($_SESSION['wpb_post_view_on_searched-'. $postID])){
          $_SESSION['wpb_post_view_on_searched-'. $postID] = $postID;
          $count++; 
          update_post_meta($postID, $count_key, $count);
        }        
    }

}

 # VIEW
/*$popularpost = new WP_Query( array( 'posts_per_page' => 4, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );
while ( $popularpost->have_posts() ) : $popularpost->the_post();
the_title();
endwhile;*/
// [popularpost total="foo-value"]
function popularpost( $atts ) {
    $a = shortcode_atts( array(
        'total' => 4
    ), $atts );
    $html = '';
    $popularpost = new WP_Query( array( 
        'posts_per_page'  => 4, 
        'meta_key'        => 'wpb_post_views_count', 
        'orderby'         => 'meta_value_num', 
        'order'           => 'DESC'  ) 
    );
    while ( $popularpost->have_posts() ) : $popularpost->the_post();
      $html .= '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
    endwhile;
    wp_reset_postdata();
    
    return '
            <ul class="custom-ul">
              '.$html.'
            </ul>
    ';
}
add_shortcode( 'popularpost', 'popularpost' );
/*POST VIEW*/

/*******POST VIEW END***********************************************************/

function get_hidden_fields($form_fields= array()){
  $query_string = $_SERVER['QUERY_STRING'];
  if($query_string) {
    parse_str($query_string, $query_string);
    foreach ($query_string as $key => $value) {
          if(!in_array($key, $form_fields)) {
              echo '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
          }              
    }
  } 
} //get_hidden_fields

function get_post_images($post_id = '', $source = false, $default=false){

	global $post;

	if ( has_post_thumbnail()) {
		$thumb = get_the_post_thumbnail( $post->ID, 'post-thumbnail', array( 'title' => esc_attr( get_the_title() ) ) );
	} else {

		$args = array(
					'post_type' => 'attachment',
					'numberposts' => 1,
					'post_mime_type' => 'title',
					'post_status' => null,
					'post_parent' => $post->ID,
				);

		$first_attachment = get_children( $args );

		if ( $first_attachment ) {
			foreach ( $first_attachment as $attachment ) {
				$thumb = wp_get_attachment_image( $attachment->ID, 'post-thumbnail', false, array( 'title' => esc_attr( get_the_title() ) ) );
			}
		} else {
			if($default) {
				$thumb = '<img src="'.get_bloginfo('template_directory').'/images/default.png" width="200"/>';
			}
		}
	}

} //get_post_images

# add class in menu
function special_nav_class($classes, $item) {

  if( in_array( 'current-menu-item', $classes ) ||
    in_array( 'current-menu-ancestor', $classes ) ||
    in_array( 'current-menu-parent', $classes ) ||
    in_array( 'current_page_parent', $classes ) ||
    in_array( 'current_page_ancestor', $classes )
    ) {

    $classes[] = "active";
  }

  return $classes;
} //special_nav_class
#add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

/* Add a wrapper to all oembed links */
function add_oembed_wrapper( $html, $url, $attr, $post_id ) {

    return '<div class="lj-mw560"><div class="lj-vidWrapper">' . $html . '</div></div>';

} add_filter( 'embed_oembed_html', 'add_oembed_wrapper', 10, 4 );

add_action('init', 'myStartSession', 1);
#add_action('wp_logout', 'myEndSession');
#add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}

/*******downlload file END***********************************************************/
function downloadit($cs_file=''){
  global $post;


  $c = 'ltexgqxpnsk1inz18jfcihtqhrflx1urw2gdsr43';    
  if( isset($_SERVER['HTTP_REFERER'] ) && isset($_GET[$c]) && $_GET[$c] ){

      /*$file = $_GET[$c];
      if( isset($_GET['file']) && $_GET['file'] ) {
        $file_id = $_GET['file'];
        $cs_file['id'] = $file_id;
        $cs_file['mime_type'] = get_post_mime_type( $file_id );
      } else {
        if(is_home()){
          return false;
        }
        
      }*/

      if(!$cs_file) {
        $cs_file = get_field('cs_file', $post->ID);
        echo $cs_file;
        if(!$cs_file) {
          return false;       
        }
      } 

      #$_SESSION['downloaded_'.get_the_ID()] = get_the_ID();

      $attach = $cs_file;

      if($attach) {
        #$download_file = $attach['url'];
        $download_file = get_attached_file( $attach['id']); 
        if(!$download_file) {
          return false;
        }         

        if($_SERVER['HTTP_HOST'] == 'localhost') {
          $download_file = str_replace("\\", "/", $download_file);
        }
        $basename = basename($download_file);
        $length   = filesize($download_file);

        // required for IE
        if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }
        
        header('Content-Description: File Transfer');
        header("Content-type: " . $attach['mime_type']); 
        header('Content-Disposition: attachment; filename="' . $basename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $length);

        /*flush();
        ob_clean(); 

        set_time_limit(0);
        readfile($download_file); */            
          ob_clean();

          ob_end_flush();

        readfile($download_file);
        exit();

      }
  }

} //downloadit
#downloadit();

/*******downlload file END***********************************************************/

function content_trim($text, $length) {
  // if the text is longer than the length it is supposed to be
  if (strlen($text) > $length){
    // trim to length
    $text = substr($text, 0, $length);
    // find last whitespace in string
    $last_whitespace = strrpos($text, ' ');
    // trim to last whitespace in string
    $text = substr($text, 0, $last_whitespace);
    // append dots
    return $text.' ...';
  }
  // if the text is shorter than the trim limit, pass it on
  else {
    return $text;
  }
} //content_trim

function current_url($display=true, $remove='', $q=true){
    if($q) {
      $query_string = $_SERVER['QUERY_STRING'];
      if($query_string) {
          parse_str($query_string, $query_string);
          
          if($remove) {
            if(isset($query_string[$remove])) {
              UNSET($query_string[$remove]);
            }        
          }

          $query_string = http_build_query($query_string);
      }
    } else {
      $query_string = '';
    }
    # = $query_string ? '?'.$query_string : '';
    #$wp->query_string
    global $wp;
    $current_url = add_query_arg( $query_string, '', home_url( $wp->request ) ).''.($query_string ? '&':'?');

    if($display) {
      echo $current_url;
    } else {
      return $current_url;
    }
}//current_url

function page_404() {
    global $wp_query; //$posts (if required)
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    die();
}

/*******For Developer use only***********************************************************/

function _get_template_html( $template_name, $attributes = null ) {
  if ( ! $attributes ) {
    $attributes = array();
  }

  ob_start();

  require( get_template_directory() . '/' . $template_name . '.php');

  $html = ob_get_contents();
  ob_end_clean();

  return $html;
}

if(!function_exists('pr')) {

    function pr($arr, $d=false) {
        echo "<pre>";
        if(is_array($arr) || is_object($arr)) {
          echo "Count: ".count($arr);
          print_r($arr);
        } else {
          echo "String: ";
          echo $arr;
        }
        echo "</pre>";

        if($d) die();
    }//pr

}

/*******For Developer use only END***********************************************************/

#https://code.tutsplus.com/articles/how-to-create-a-simple-post-rating-system-with-wordpress-and-jquery--wp-24474
#
function star_rating_theme_flat_non_user()
  {
    $data_id = get_the_ID();
    
    $totalstarvalue = (int)get_post_meta( $data_id, 'totalstarvalue', true );
    if(empty($totalstarvalue)) $totalstarvalue = 0;

    $totalstarcount = (int)get_post_meta( $data_id, 'totalstarcount', true );
    if(empty($totalstarcount)) $totalstarcount = 1;
    
    $rate = $totalstarvalue/$totalstarcount;
    
    if($rate>5)
      {
        $rate = 5;
      }
      
    $rate = number_format($rate, 2);
    $rate_int = ceil($rate);
    
      
    $html = '';
    $html .= '<div  class="star-rating anormal star-no-login star-rating'.get_the_ID().' flat" data_id="'.get_the_ID().'" currentrate="'.$rate.'" data-toggle="tooltip" title="Login to rate this business.">';
    
    $i= 1;
    while($i<=5)
      {
        if($i <= $rate_int)
          {
            $html .= '<div class="star_'.$i.'  ratings_over nolink" starvalue="'.$i.'" ></div>';
          }
        else
          {
            $html .= '<div class="star_'.$i.' no_ratings_stars nolink" starvalue="'.$i.'" ></div>';
          }
        
        
        $i++;
      }
    
    
    $html .= '<div class="total_votes">'.$rate.'</div>
    ';

    $html .= '</div>'; // end 
    

    return $html;

    
    
  }