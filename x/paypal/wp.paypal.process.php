<?php
/**
* manage paypal account
*/

define('LOG_FILE', dirname( __FILE__ ).'/ipn_results.log');
define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('SSL_SAND_URL','https://www.sandbox.paypal.com/cgi-bin/webscr');

define('IPN_EMAIL', 'link4anything@yahoo.com');

class fz_paypal_process {

    var $notification;
    var $success;
    var $paypal_sandbox;
    var $return_url;
    var $notify_url;
    var $cancel_url;
    var $currency_code;
    var $formdata;
    var $date_start;
    var $date_end;

    private $ipn_status     = '';                       // holds the last status
    public  $admin_mail     = '';        // receive the ipn status report pre transaction
    public  $paypal_mail    = '';                       // paypal account, if set, class need to verify receiver
    public $txn_id          = null;                          // array: if the txn_id array existed, class need to verified the txn_id duplicate
    public $ipn_log         = true;                         // bool: log IPN results to text file?
    private $ipn_response   = '';                     // holds the IPN response from paypal   
    public $ipn_data        = array();                     // array contains the POST values for IPN
    private $fields         = array();                      // array holds the fields to submit to paypal
    private $ipn_debug      = false;                     // ipn_debug


    function __construct() {
        
        $this->set_variable();                    
        add_action('init', [$this, 'process']);    
        add_action('init', [$this, 'process_ipn']);    
    }


    function set_variable(){
        $this->success          = false;
        $this->formdata         = '';
        $this->paypal_mail      = get_option('paypal_email');
        $this->paypal_sandbox   = get_option('paypal_sandbox');
        
        $this->currency_code   = 'AUD';        
        $this->return_url   = get_bloginfo( 'url' ).'/profile/?profile=product&a=processpayment';
        $this->notify_url   = get_bloginfo( 'url' ).'/?a=ipn';
        $this->cancel_url   = get_bloginfo( 'url' ).'/register-your-business/checkout/?a=cancel';

        $this->date_start   = '';
        $this->date_end     = '';        
    } //set_variable()

    public function process() {
        
        if(!isset($_POST)) return false;

        $post_data  = $_POST;        
        if(!isset($post_data['paypal_action']) || !isset($post_data['paypal_process_nonce']) || !wp_verify_nonce($post_data['paypal_process_nonce'], 'paypal-process-nonce')) {
            return false;
        }        
        #process for logged user only
        if ( !is_user_logged_in() ) return false;
        
        unset($_POST);
        $this->formdata = $post_data;

        $action = $post_data['paypal_action'];

        switch ($action) {
            case 'processpayment':      
                $this->postform($post_data);                
                break;
            
            default:
                return false;
                break;
        }

        $this->display_error();
    } //process()

    function postform($post_data){
        global $profiler;

        #pr($post_data);
        $user_id    = get_current_user_id();
        $package_id = $post_data['business']['package_id'];

        $packages = $profiler->packages;
        if(isset($packages[$package_id])) {
            $business   = $profiler->get_vendor($user_id);
            $post_id    = $business->ID;
            
            $error      = array();
            $error = $profiler->validation_billing($post_data);
            if($error) {            
                $this->notification = $error;
                return false;
            }

            $profiler->update_userbilling($post_data, false);

            #$this->upgradeaccount($user_id, $post_id, $package_id);
            #die('test upgrade');
            $package  = $packages[$package_id];
        
            $user_info  = get_userdata($user_id);

            $data   = array(
                            'user_id'       => $user_id,
                            'post_id'       => $post_id,
                            'package_id'    => $package_id
                            );

            $query                      = array();
            $query['business']          = $this->paypal_mail;
            $query['cmd']               = '_xclick';
            $query['currency_code']     = $this->currency_code;
            $query['image_url']         = ASSETS_IMG.'logo.png';
            $query['upload']            = 1;
            $query['cancel_return']     = $this->cancel_url."&package_id=$package_id";
            $query['return']            = $this->return_url; 
            $query['notify_url']        = $this->notify_url;
            $query['custom']            = http_build_query($data);
            $query['cbt']               = "Return to Sports Finda";
            $query['item_name']         = $package['title'];
            $query['amount']            = $package['price'];
            
            $query['first_name']        = $post_data['billing_first_name'];
            $query['last_name']         = $post_data['billing_last_name'];
            $query['address1']          = $post_data['billing_address'];
            $query['city']              = $post_data['billing_city'];
            $query['country']           = $post_data['billing_country'];
            $query['state']             = $post_data['billing_state'];
            $query['zip']               = $post_data['billing_post_code'];
            $query['note']              = $post_data['ordernote'];
            $query['email']             = $user_info->user_email;
                        
            #pr($post_data);
            #pr($query, true);
            $query_string   = http_build_query($query);

            if($this->paypal_sandbox) {
                $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string;
            } else {
                $url = 'https://www.paypal.com/cgi-bin/webscr?' . $query_string;            
            }
            header("Location: $url"); exit;   
        } else {
            $error      = array();
            $error[]    = 'Invalid Package';
            $this->notification = $error; 
        }     
    } //postform

    function recordpayment(){
        
        $postdata = $this->ipn_data;
        $postdata = $postdata['custom'];
        
        parse_str($postdata, $data);
        $data = json_decode( json_encode($data) );                
        
        $postdata               = $this->ipn_data;
        $postdata['user_id']    = $data->user_id;
        $postdata['post_id']    = $data->post_id;
        $postdata['package_id'] = $data->package_id;

        $paypal_txn_id  = $postdata['txn_id'];            
        $insert_ipn_array = array(
            'post_type' => 'paypal_ipn', // Custom Post Type Slug
            'post_status' => 'publish',
            'post_title' => $paypal_txn_id,
        );

        $post_id = wp_insert_post($insert_ipn_array);
        if($post_id) {          
            $postdata['date_start'] = $this->date_start;
            $postdata['date_end']   = $this->date_end; 
            $this->ipn_response_postmeta_handler($post_id, $postdata);
        }

        return $post_id;
    } //recordpayment
    
    function ipn_response_postmeta_handler($post_id, $posted) {
        update_post_meta($post_id, 'ipn data serialized', $posted);
        foreach ($posted as $metakey => $metavalue)
            update_post_meta($post_id, $metakey, $metavalue);
    }

    function upgradeaccount($user_id, $post_id, $package_id){

        $user_info = get_userdata($user_id);
        if($user_info) {
            global $profiler;

            $packages = $profiler->packages;            
            if(isset($packages[$package_id])) {
                $package  = $packages[$package_id];
                #update business package and publish it
                $meta_key = $profiler->get_acf_key('packages');
                update_field( $meta_key, $package_id, $post_id);
                // Update post
                $new_post                   = array();
                $new_post['ID']             = $post_id;
                $new_post['post_status']    = 'publish';

                // Update the post into the database
                wp_update_post( $new_post );  

                // Update Subscription date_start and end
                $this->updatepackages_expiration($package_id, $post_id); 
                             
                #update role
                $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $profiler->vendor_role ) );

                if ( is_wp_error( $user_id ) ) {
                    // Log the response from the paypal server
                    $this->ipn_status .= "ERROR in upgrading account [ user_id: $user_id, post_id: $post_id, package_id: $package_id ] \n";
                    $this->write_to_log ();                    
                } else {
                    #sent notification
                    $this->wp_new_vendor_notification( $user_id, $post_id, $package['title']);
                }                   
            }
        }

    } //upgradeaccount

    function updatepackages_expiration($package_id, $post_id){
        global $profiler;
        
        $date_start = get_field('date_start', $post_id );
        $date_end   = get_field('date_end', $post_id );

        #echo "OLD $date_start to $date_end <br />";
        $package    = $profiler->get_package($package_id);
        if($package) {
            $year = $package['year'];
        
            if($date_start) {
                if($profiler->is_vendor_valid( $date_start, $date_end, false)) {
                    // change date_end
                    $date_restart = $date_end;
                } else {
                    $date_start     = date("m/d/Y"); // change start date
                    $date_restart   = date("m/d/Y");
                }

                $date_end = date('m/d/Y', strtotime( $date_restart . "+$year year"));
            } else {
                $date_start = date("m/d/Y"); //09/01/2016
                $date_end   = date('m/d/Y', strtotime( $date_start . "+$year year"));
            }

            $meta_key = $profiler->get_acf_key('date_start');
            update_field( $meta_key, $date_start, $post_id);
            
            $meta_key = $profiler->get_acf_key('date_end');
            update_field( $meta_key, $date_end, $post_id);                    
            #echo "<br />NEW $date_start to $date_end";
            $this->date_start   = $date_start;
            $this->date_end     = $date_end;
        }
        #die('test');
    } //updatepackages_expiration

    function wp_new_vendor_notification( $user_id, $post_id, $package) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(__('New Business Registered :'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Email: %s'), $user_email) . "\r\n";
        #$message .= "Business name: <a href='".get_permalink($post_id)."'>".get_the_title($post_id)."</a> \r\n";
        $message .= "Business name: ".get_the_title($post_id)." \r\n";
        $message .= "Package: $package \r\n";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New Business Registration'), get_option('blogname')), $message);


        $message  = __('Hi there,') . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";        
        $message .= sprintf(__('Email: %s'), $user_email) . "\r\n\r\n";
        #$message .= "Business name: <a href='".get_permalink($post_id)."'>".get_the_title($post_id)."</a> \r\n";        
        $message .= "Business name: ".get_the_title($post_id)." \r\n";
        $message .= "Package: $package \r\n";

        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __(get_option('blogname'));

        wp_mail($user_email, sprintf(__('[%s] Business Account Info'), get_option('blogname')), $message);

    } //wp_new_user_notificationV2

    function process_ipn() {
        global $profiler;

        if(isset($_GET['a']) && $_GET['a']) {

            $action = $_GET['a'];

            switch ($action) {
                case 'ipn':      
                    if($this->validate_ipn()) {
                        $postdata  = $_POST;
                        $this->ipn_data = $postdata;
                        
                        $this->process_payment_transaction();              
                    }           
                    break;
                case 'processpayment':  
                    if(isset($_POST['payer_status'])) {
                        wp_redirect($this->return_url);exit;
                    }
    
                    $business   = $profiler->get_vendor();
                    $post_id    = $business->ID;                    
                    $error      = array();
                    $error[]    = 'Payment completed. View my <a href="'.get_permalink($post_id).'">Business Profile</a>';
                    $this->success      = true; 
                    $this->notification = $error; 
                    $this->display_error();            
                    break;            
                default:
                    return false;
                    break;
            }            
            unset($_POST);
        }
        #pr($_POST);
        #return false;      
    }//process_ipn

    function process_payment_transaction(){
        $postdata = $this->ipn_data;

        $ipn_data = $postdata['custom'];
        parse_str($ipn_data, $data);
        $data = json_decode( json_encode($data) );                
        
        /*$this->ipn_status = "\n ".print_r($data, true)."  \n\n";
        $this->write_to_log ();*/

        $user_id    = $data->user_id;
        $post_id    = $data->post_id;
        $package_id = $data->package_id;
        $this->upgradeaccount($user_id, $post_id, $package_id);
        $this->recordpayment();

        $this->ipn_status = "Upgrade User ID: $user_id, Package: $package_id, Post ID: $post_id  at ".date("F j, Y");
        $this->write_to_log();
        die('Not Found');  
    }//process_payment_transaction

    /**
     * validate the IPN
     * 
     * @return bool IPN validation result
     */
    function validate_ipn() {
        
        $hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
        if (! preg_match ( '/paypal\.com$/', $hostname )) {
            $this->ipn_status = 'Validation post isn\'t from PayPal'.$hostname;
            $this->log_ipn_results ( false );
            return false;
        }
        
        if (isset($this->paypal_mail) && strtolower ( $_POST['receiver_email'] ) != strtolower(trim( $this->paypal_mail ))) {
            $this->ipn_status = "Receiver Email Not Match, Business: $this->paypal_mail, Receiver: ".$_POST['receiver_email'];
            $this->log_ipn_results ( false );
            return false;
        }
        
        if (isset($this->txn_id)&& in_array($_POST['txn_id'],$this->txn_id)) {
            $this->ipn_status = "txn_id have a duplicate";
            $this->log_ipn_results ( false );
            return false;
        }

        // parse the paypal URL
        if (isset($_POST['test_ipn']) ) {
            $paypal_url = SSL_SAND_URL;
        } else {
            $paypal_url = SSL_P_URL;
        }
        $url_parsed = parse_url($paypal_url);        
        
        // generate the post string from the _POST vars aswell as load the
        // _POST vars into an arry so we can play with them from the calling
        // script.
        $post_string = '';    
        foreach ($_POST as $field=>$value) { 
            $this->ipn_data["$field"] = $value;
            $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
        }

        $post_string.="cmd=_notify-validate"; // append ipn command
        
        // open the connection to paypal
        if (isset($_POST['test_ipn']) )
            $fp = fsockopen ( 'ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60 );
        else
            $fp = fsockopen ( 'ssl://www.paypal.com', "443", $err_num, $err_str, 60 );
 
        if(!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            $this->ipn_status = "fsockopen error no. $err_num: $err_str";
            $this->log_ipn_results(false);       
            return false;
        } else { 
            // Post the data back to paypal
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
            fputs($fp, "Host: $url_parsed[host]\r\n"); 
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
            fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
            fputs($fp, "Connection: close\r\n\r\n"); 
            fputs($fp, $post_string . "\r\n\r\n"); 
        
            // loop through the response from the server and append to variable
            while(!feof($fp)) { 
            $this->ipn_response .= fgets($fp, 1024); 
           } 
          fclose($fp); // close connection
        }
        
        // Invalid IPN transaction.  Check the $ipn_status and log for details.
        if (! eregi("VERIFIED",$this->ipn_response)) {
            $this->ipn_status = 'IPN Validation Failed';
            $this->log_ipn_results(false);   
            return false;
        } else {
            $this->ipn_status = "IPN VERIFIED";
            $this->log_ipn_results(true); 
            return true;
        }
    }

    function log_ipn_results($success) {
        $hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
        // Timestamp
        $text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
        // Success or failure being logged?
        if ($success)
            $this->ipn_status = $text . 'SUCCESS:' . $this->ipn_status . "!\n";
        else
            $this->ipn_status = $text . 'FAIL: ' . $this->ipn_status . "!\n";
       
            // Log the POST variables
        $this->ipn_status .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]IPN POST Vars Received By Paypal_IPN Response API:\n";
        
        foreach ( $this->ipn_data as $key => $value ) {
            $this->ipn_status .= "$key=$value \n";
        }
        // Log the response from the paypal server
        $this->ipn_status .= "IPN Response from Paypal Server:\n" . $this->ipn_response;
        $this->write_to_log ();
    }
    
    function write_to_log() {
        if (! $this->ipn_log)
            return; // is logging turned off?

        // Write to log
        $fp = fopen ( LOG_FILE , 'a' ) or die("Unable to open file!");
        fwrite ( $fp, $this->ipn_status . "\n\n" );
        fclose ( $fp ); // close file
        #chmod ( LOG_FILE , 0600 );
    }

    /*$error      = array();
    $error[]    = 'Failed to register user. Please contact administrator.';
    $this->notification = $error; 
    $this->display_error();
    do_action( 'process-notification' );
    */
    function display_error(){

        if(!$this->notification) return;    

        add_action('process-notification', function(){

            $html = ''; $newline = '';
            if($this->success) {
                $class = "success";
                $label = "Success";
            } else {
                $class = "danger";
                $label = "ERROR";                
            }
            foreach ($this->notification as $key => $value) {
                $html .= "$newline<strong>$label</strong>: $value."; $newline = '<br>';
            }            
            echo $html ? '
            <div class="alert alert-'.$class.' fade in ">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                '.$html.'
            </div>' : '';
        });        
    } //display_error

} // sac_template    


if(! is_admin()) $paypal_process = new fz_paypal_process();

/*if(! is_admin()) {
    $paypal_process->ipn_data = (array) json_decode('{"mc_gross":"500.00","protection_eligibility":"Eligible","address_status":"confirmed","payer_id":"WULXB4WEBQZJL","tax":"0.00","address_street":"1 Main St","payment_date":"06:29:39 Aug 30, 2016 PDT","payment_status":"Completed","charset":"windows-1252","address_zip":"95131","first_name":"Fritz","mc_fee":"14.80","address_country_code":"US","address_name":"Fritz Roca","notify_version":"3.8","custom":"user_id=11&post_id=592&package_id=ultimate","payer_status":"verified","business":"link4anything-facilitator@yahoo.com","address_country":"United States","address_city":"San Jose","quantity":"1","verify_sign":"AQ.EyW2RDFkNaZFEnbsBbam6GcC4A0OZs6ESeoDH7G-Oq7jzzNodATE6","payer_email":"link4anything@yahoo.com","txn_id":"2SY238304W1667152","payment_type":"instant","last_name":"Roca","address_state":"CA","receiver_email":"link4anything-facilitator@yahoo.com","payment_fee":"14.80","receiver_id":"EMWP3FJXSE3RY","txn_type":"web_accept","item_name":"Ultimate Plan","mc_currency":"USD","item_number":"","residence_country":"US","test_ipn":"1","handling_amount":"0.00","transaction_subject":"","payment_gross":"500.00","shipping":"0.00","ipn_track_id":"444eae478047b"}');
    $paypal_process->process_payment_transaction();
}*/
?>