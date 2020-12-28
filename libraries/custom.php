<?php
//custom function

#add_action('init', 'myStartSession', 1);
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

function page_404() {
    global $wp_query; //$posts (if required)
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    die();
}

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