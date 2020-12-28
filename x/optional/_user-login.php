<?php
#http://www.paulund.co.uk/create-your-own-wordpress-login-page

function custom_login_page() {
 $new_login_page_url = home_url( '/login/' ); // new login page
 global $pagenow;
 if( $pagenow == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
    wp_redirect($new_login_page_url);
    exit;
 }
}

if(!is_user_logged_in()){
 add_action('init','custom_login_page');
}


add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login

function pu_login_failed( $user ) {
    // check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    // check that were not on the default login page
  if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
    // make sure we don't already have a failed login attempt
    if ( !strstr($referrer, '?login=failed' )) {
      // Redirect to the login page and append a querystring of login failed
        wp_redirect( $referrer . '?login=failed');
      } else {
          wp_redirect( $referrer );
      }

      exit;
  }
}


add_action( 'authenticate', 'pu_blank_login');

function pu_blank_login( $user ){
    // check what page the login attempt is coming from
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    $error = false;

    if($_POST['log'] == '' || $_POST['pwd'] == '') {
      $error = true;
    }

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {

      // make sure we don't already have a failed login attempt
      if ( !strstr($referrer, '?login=failed') ) {
        // Redirect to the login page and append a querystring of login failed
          wp_redirect( $referrer . '?login=failed' );
        } else {
          wp_redirect( $referrer );
        }

    exit;

    }
}

/*
                $args = array( 'redirect' => site_url() );

                if(isset($_GET['login']) && $_GET['login'] == 'failed')
                {
                    ?>
                        <div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
                            <p>Login failed: You have entered an incorrect Username or password, please try again.</p>
                        </div>
                    <?php
                }

                wp_login_form( $args );

*/