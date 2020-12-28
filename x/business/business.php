<?php
/**
* class for profile management
*/

class sac_profiler {

    var $notification;
    var $success;
    var $checkout_url;
    var $profile_url;
    var $page_id_reg_biz;
    var $vendor_status;
    var $packages;
    var $vendor_role;
    var $post_data;
    var $vendor_field_id;
    var $vendor_product_field_id;
    var $vendor_location_field_id;
    var $billing_field_id;

    function __construct() {
        
        $this->set_variable();
        add_action('init', [$this, 'process_action']);    
        add_action('init', [$this, 'register_my_session']);            
        add_action('init', [$this, 'session_notification']);            
        add_shortcode( 'profiler-register', [$this, 'registration_form'] );                         
    }

    function set_variable(){
        #temporary ()
        $this->success          = false;
        $this->page_id_reg_biz  = 530;
        $this->page_id_pckg_biz = 347;
        $this->vendor_field_id  = 204; # FIELD Business/Vendor Field
        $this->vendor_product_field_id = 559; # FIELD Business Product Portfolio/Products
        $this->vendor_location_field_id = 569; # FIELD Business location
        $this->billing_field_id = 600; # FIELD Billing Information
        $this->post_data        = array();

        $this->biz_url          = get_bloginfo( 'url' ).'/register-your-business/';
        $this->biz_reg_url      = get_permalink( $this->page_id_reg_biz );
        $this->checkout_url     = get_bloginfo( 'url' ).'/register-your-business/checkout/';
        $this->profile_url      = get_bloginfo( 'url' ).'/profile/';
        $this->vendor_status    = false; 
        $this->vendor_role      = 'vendor'; 
        
        $price_packages = get_field('price_packages', $this->page_id_pckg_biz);
        
        $packages = array();
        foreach ($price_packages as $key => $package) {
            $packages[$package['package_id']] = $package;
        }
        #pr($packages, true);
        $this->packages         = $packages;
    } //set_variable()

    function get_select_package($package_id = ''){
        $html = '';
        foreach($this->packages as $key => $package) {
            $name   = $package['title'] .' (US$'.$package['price'].'/year) ';
            $html .= '<option value="'.$key.'" '.($key == $package_id ? 'SELECTED' : '').'>'.$name.'</option>';
        }
        $html = '
                <select name="business[package_id]" class="form-control">
                    <option value="" disabled selected>Pricing Packages*</option>
                    '.$html.'
                </select>';
        echo $html;
    }//get_select_package()

    function get_package($package_id) {

        $packages = $this->packages;
        return isset($packages[$package_id]) ? $packages[$package_id] : false;

    }

    public function process_action() {
        $error      = array();

        if(!$_POST) return false;

        $post_data  = $_POST;        
        $this->post_data = $post_data;
        if(!isset($post_data['biz_action']) || !isset($post_data['biz_action_nonce']) || !wp_verify_nonce($post_data['biz_action_nonce'], 'biz-action-nonce')) {
            
            $error[] = 'Invalid action';
        }
        
        $action = isset($post_data['biz_action']) ? $post_data['biz_action'] : '';
        
        switch ($action) {
            case 'register-business':      
                $this->register($post_data);   
                unset($_POST);             
                break;
            case 'update-information':                      
                $this->update_information($post_data);  
                break;            
            case 'update-billing':                      
                $this->update_userbilling($post_data);
                break;   
            case 'update-product':                 
                $this->update_product();
                unset($_POST);
                break; 
            case 'update-product-gallery':                 
                $this->update_product_gallery();
                break;                                
            default:
                return false;
                break;
        }

        #unset($_POST);
        $this->display_error();
    } //process()
    
    function update_information($postdata){
        $user_id    = get_current_user_id();
        $user_info  = get_userdata($user_id);
        $postdata['confirm_email'] = $user_info->user_email;

        $error = array();
        $error = $this->validation_information($postdata);
        #pr($error);
        if($error) {
            $this->notification = $error;
            return false;
        } else {
            $user_id    = get_current_user_id();
            $post_user  = $postdata['user'];
            
            $userdata['ID']         = $user_id;
            $userdata['first_name'] = $post_user['first_name'];
            $userdata['last_name'] = $post_user['last_name'];
            $userdata['user_email'] = $post_user['email'];
            if(isset($post_user['pass1']) && $post_user['pass1']) {
                $userdata['user_pass'] = $post_user['pass1'];
            }
            $user_id = wp_update_user($userdata); 
            $error = array();
            if ( is_wp_error( $user_id ) ) {
                $error[] =  'Information not saved';
            } else {
                $this->success = true;
                $error[] =  'Information successfully saved';
                $this->notification = $error;
                $this->redirect_notification($this->profile_url.'?profile=information');
            }                     
            $this->notification = $error;
        }
        return true;
    }//update_information

    function update_userbilling($postdata, $redirect = true) {
        $error = $this->validation_billing($post_data);
        if($error) {                
            $this->notification = $error;
            return false;
        } 
        $user_id    = get_current_user_id();       
        
        $fields = acf_get_fields($this->billing_field_id); # FIELD Billing Information
        foreach ($fields as $row) {
            $key        = $row['name'];
            $meta_key   = $row['key'];
            if(isset($postdata[$key])) {
                update_field( $meta_key, $postdata[$key], 'user_'.$user_id);
            }
        }   
        if(!$redirect){
            return true;
        }
        $error = array();
        $error[] =  'Billing Information successfully saved'; 
        $this->success = true;
        $this->notification = $error;
        $this->redirect_notification($this->profile_url.'?profile=billing');
    } //updateuserbilling

    function update_product(){
        global $fz_helper;
        $post_data = $this->post_data;    
        $post_id = $post_data['post_id'];

        // get vendor data
        $post = $this->get_vendor_data();
        
        $package = $this->get_package($post['business']['packages']);
        #pr($package);        

       
        if($post_data['business_video_id']) {
            $attach_id = $post_data['business_video_id'];
            $meta_key = $this->get_acf_key('business_video');
            update_field( $meta_key, $attach_id, $post_id);            
        }  

        /*validation*/
        $error = array();
        $error = $this->validate_business($post_data['business'], $error);
       
        // validate category
        $category = array();
        if(isset($post_data['business']['category'])) {
            $category = $post_data['business']['category'];
            if(($package['categories'] != -1) && count($category) > $package['categories']) {
                $error[] = 'Packages only allow '.$package['categories'].' categories';               
            }
        }
        if($error) {
            $this->notification = $error;
            return false;
        } 
        /*validation*/

        // Update post
        $new_post = array(
          'ID'           => $post_id,
          'post_title'   => $post_data['business']['name'],          
        );   

        if($package['business_description']) {
            $new_post['post_content'] = $post_data['business']['content'];
        }

        $post_id = wp_update_post( $new_post, true );                         
        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            $error = array();
            foreach ($errors as $err) {
                $error[] = $err;
            }

            $this->notification = $error;
            return false;
        }
        
        /*upload logo*/
        /*if(isset($_FILES['business_image']) && $_FILES['business_image']['name']) {
            if($this->validate_image_upload($_FILES["business_image"])) {
                $this->register_business_featured_image($post_id, $_FILES['business_image'], 'business_image');
            } else {
                $error[] =  'Invalid Image Type';
            }         
        }*/
        if($post_data['business_image_id']) {
            $attach_id = $post_data['business_image_id'];
            set_post_thumbnail( $post_id, $attach_id );
        }
        
        if($post_data['business_logo_id']) {
            $attach_id = $post_data['business_logo_id'];
            $meta_key = $this->get_acf_key('business_logo');
            update_field( $meta_key, $attach_id, $post_id);            
        } 
        /*upload logo*/
        
        /*upload video*/
        /*if($package['upload_video']) {            
            if(isset($_FILES['business_video']) && $_FILES['business_video']['name']) {
                if($this->validate_video_upload($_FILES["business_video"])) {
                    $attach_id = $this->handle_attachment('business_video', $post_id);
                    if($attach_id) {                    
                        $meta_key = $this->get_acf_key('business_video');
                        update_field( $meta_key, $attach_id, $post_id);
                    }
                } else {
                    $error[] =  'Invalid Video Type';
                }         
            }
        }*/

        /*if($post_data['business_video_id']) {
            $attach_id = $post_data['business_video_id'];
            $meta_key = $this->get_acf_key('business_video');
            update_field( $meta_key, $attach_id, $post_id);            
        } */
        $parsepost = array();
        foreach ($post_data['business']['business_video_url'] as $key => $row) {
            $video = array();
            if($row) {
                #$video['video'] = 'url';                
                $video['business_video_url'] = $row;                
                #$video['business_video']     = '';                
            }/* else if($post_data['business']['business_video_id'][$key]) {
                $video['video'] = 'file';
                $video['business_video_url'] = '';                
                $video['business_video'] = $post_data['business']['business_video_id'][$key];   
            }*/
            if($video){
                $parsepost[] = $video;
            }
        }
        if(count($parsepost) > $package['videos']) {
            $error[] =  '<strong>ERROR:</strong> You can only add '.$package['videos'].' business videos.'; 
        } else if($parsepost) {
            $acf_key = $this->get_acf_key('business_videos');
            update_field( $acf_key, $parsepost, $post_id);  
        }                
        /*upload video*/

        /* update post category*/
        wp_set_post_terms( $post_id, $category, 'business-type' );
        /* update post category*/
        
        /* update meta fields*/
        $meta_fields = $post_data['business'];
        $fields = acf_get_fields($this->vendor_field_id); # FIELD Business/Vendor Field
        foreach ($fields as $row) {
            $key        = $row['name'];
            $meta_key   = $row['key'];
            if(isset($meta_fields[$key])) {                
                update_field( $meta_key, $meta_fields[$key], $post_id);
            }
        } 
        /*location*/
        $loc_limit = 5;
        $meta_fields = $post_data['business'];
        $fields = acf_get_fields($this->vendor_location_field_id); # FIELD Business/Vendor Field
        foreach ($fields as $row) {
            $key        = $row['name'];
            $meta_key   = $row['key'];

            $loc_meta = array();
            if(isset($meta_fields[$key])) {             
                
                $maps      = $meta_fields[$key]['google_map'];                
                /* generate google map meta*/
                $location_map = array();
                foreach ($maps as $loc) {

                    if($loc) {

                        $google_map = $fz_helper->get_location_geo($loc);
                        if($google_map) {
                            $location_map[] = $google_map;
                        } else {
                            $location_map[] = array();
                        }

                    } else {
                        $location_map[] = array();
                    }
                                            
                }
                /* generate google map meta*/

                $location_title     = $meta_fields[$key]['title'];
                $location_address   = $meta_fields[$key]['address'];
                $location_state     = $meta_fields[$key]['state'];
                $location_postcode  = $meta_fields[$key]['postcode'];
                $location_country   = $meta_fields[$key]['country'];
                $loc_meta = array();
                foreach ($location_title as $key => $row) {
                    $loc_meta[$key]['google_map']   = $location_map[$key];
                    $loc_meta[$key]['title']        = $location_title[$key];
                    $loc_meta[$key]['address']      = $location_address[$key];
                    $loc_meta[$key]['state']        = $location_state[$key];
                    $loc_meta[$key]['postcode']     = $location_postcode[$key];
                    $loc_meta[$key]['country']      = $location_country[$key];
                }                                
            }
            if(count($loc_meta) > $loc_limit) {            
                $error[] =  "<strong>ERROR:</strong> Only $loc_limit location allowed.";
            } else {
                update_field( $meta_key, $loc_meta, $post_id);
            }
        }

        #$meta_key = $this->get_acf_key('business_video_url');
        #update_field( $meta_key, $post_data['business']['business_video_url'], $post_id); 
                
        $meta_key = $this->get_acf_key('additional_information');
        update_field( $meta_key, $post_data['business']['additional_information'], $post_id); 
        /* update meta fields*/

        $this->success = true;
        $error[] =  'Business successfully saved';
        $this->notification = $error;
        $this->redirect_notification($this->profile_url.'?profile=profile');        
        return true;
    } //update_product

    function update_product_gallery(){
        
        $error = array();
        
        $post_data = $this->post_data;        
        
        $post_id            = $post_data['post_id'];        
        $post_product       = $post_data['gallery'];
        $gallery_image_id   = $post_data['gallery_image_id'];
        #$post_file          = $_FILES['gallery'];

        $parsepost = array();
        foreach ($post_product as $key => $vals) {
            foreach ($vals as $k => $val) {
                $parsepost[$k][$key] = $val;
                if($gallery_image_id[$k]) {
                    $parsepost[$k]['image'] = $gallery_image_id[$k];
                }
            }
        }
        
        // get vendor data
        $post = $this->get_vendor_data();    
        $package = $this->get_package($post['business']['packages']);
        

        if(count($parsepost) > $package['images']) {
            $error[] =  'You can only add '.$package['images'].' product images.'; 
            $this->notification = $error;  
            return false;          
        }
        
        /*upload new images*/
        /*$attach_ids = $this->wp_upload_multifile("gallery_image", $post_id);
        foreach ($attach_ids as $key => $value) {
            if($value) {
                $parsepost[$key]['image'] = $value;
            }
        }*/
        /*upload new images*/

        #update fields        
        $acf_key = $this->get_acf_key('products');
        update_field( $acf_key, $parsepost, $post_id);
        #pr($post_file, true);

        $this->success = true;
        $error[] =  'Product Images successfully saved';
        $this->notification = $error;
        $this->redirect_notification($this->profile_url.'?profile=product');
        return true;

    }//update_product_gallery

    public function register($postdata) {

        $error = $this->validation($postdata);

        if($error) {
            $this->notification = $error;
            return false;
        } else {
            $post_clean = array();
            foreach ($postdata as $key => $value) {
                if(is_array($value)) {
                    $row = array();
                    foreach ($value as $subkey => $value) {
                        $row[$subkey] = is_array($value) ? $value : sanitize_text_field($value);    
                    }
                    $post_clean[$key] = $row;
                } else {
                    $post_clean[$key] = is_array($value) ? $value : sanitize_text_field($value);
                }
            }   
            $postdata = $post_clean;

            // check if login user
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
            } else {
                //register user        
                $username   = $postdata['user']['email'];
                #$password   = $postdata['user']['pass1'];
                $email      = $postdata['user']['email'];
                #$user_id    = wp_create_user( $username, $password, $email);
                
                // Generate the password so that the subscriber will have to check email...
                $password = wp_generate_password( 12, false );                
                $user_data = array(
                    'user_login'    => $email,
                    'user_email'    => $email,
                    'user_pass'     => $password,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'nickname'      => $first_name,
                );

                $user_id = wp_insert_user( $user_data );
                if ( is_int($user_id) ) { 
                    #sent email notification
                    $this->wp_new_user_notificationV2($user_id, $password);
                    #login and redirect to checout
                    $this->auto_login($user_id);
                }
            }

            if ( !is_int($user_id) ) {
                $error      = array();
                $error[]    = 'Failed to register user. Please contact administrator.';
                $this->notification = $error;              
                return false;
            } else {                
                #validate if user is already vendor
                $status = $this->is_vendor($user_id);
                if($status == false) {        

                    /*$userdata       = array();
                    $userdata['ID'] = $user_id;
                    $userdata['first_name'] = $postdata['user']['name'];
                    wp_update_user($userdata); */

                    #save business
                    $this->register_business($user_id, $postdata['business']);
                    #wp_redirect( $this->checkout_url ); exit;
                }
            }           
        }

        return true;

    } //register()

    function register_business($user_id, $data){
        $error      = array();
        
        $new_post = array(                      
            'post_title'    =>  $data['name'],
            'post_content'  =>  isset($data['content']) ? $data['content'] : '',
            'post_status'   =>  'pending',
            'post_type'     =>  'business',
            'post_author'   =>  $user_id,
        );
        $post_id    = wp_insert_post($new_post); 
        if($post_id) {
            /* meta value*/
            unset($data['content']);
            foreach($data as $key => $value) {
                $meta_key = $this->get_acf_key($key);            
                if($value != '') {                
                    update_field( $meta_key, $value, $post_id);
                }
            }
            /*upload featured image*/
            /*upload logo*/        
            if(isset($_FILES['business_image']) && $_FILES['business_image']['name']) {
                if($this->validate_image_upload($_FILES["business_image"])) {
                    $this->register_business_featured_image($post_id, $_FILES['business_image'], 'business_image');
                } else {
                    $error[] =  'Invalid Image Type';
                    $this->notification = $error;                     
                }         
            }
            /*upload logo*/            

            $file       = isset($_FILES['business_image']) ? $_FILES['business_image'] : array();
            $this->register_business_featured_image($post_id, $file, 'business_image');            
            wp_redirect($this->checkout_url.'?package_id='.$data['packages']);exit;
        } else {            
            $error[]    = 'Failed to register business. Please contact administrator.';
            $this->notification = $error;               
        }

    } // register_business()

    function validate_image_upload($file) {
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $extension = end(explode(".", $file["name"]));
        if ((($file["type"] == "image/gif")
        || ($file["type"] == "image/jpeg")
        || ($file["type"] == "image/jpg")
        || ($file["type"] == "image/png"))
        && ($file["size"] < 10485760)
        && in_array($extension, $allowedExts)) {
            return true;
        } else {
            return false;
        }         
    } //validate_image_upload

    function validate_video_upload($file) {
        $allowedExts = array("mp4", "3gp", "flv", "avi");
        $extension = end(explode(".", $file["name"]));
        if ((($file["type"] == "video/mp4")
        || ($file["type"] == "video/3gp")
        || ($file["type"] == "video/flv")
        || ($file["type"] == "video/png"))
        && ($file["size"] < 10485760)
        && in_array($extension, $allowedExts)) {
            return true;
        } else {
            return false;
        }         
    } //validate_video_upload

    function wp_upload_file($post_id=0, $file, $input_name){

        if(!isset($file['size']) || $file['error'] != UPLOAD_ERR_OK) return false;        
        
        include_once ABSPATH . 'wp-admin/includes/media.php';
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_handle_upload( $input_name, $post_id );
        if ( is_wp_error( $attachment_id ) ) {
            #echo $attachment_id->get_error_message();        
            return false;
        } else {            
            return $attachment_id;
        }

    }//wp_upload_file

    function wp_upload_multifile($file_key, $post_id){
        $error = array();
        $attach_ids = array();
        if ( $_FILES ) { 
            $files = $_FILES[$file_key];  
            foreach ($files['name'] as $key => $value) {            
                #if ($files['name'][$key]) { 
                    $file = array( 
                        'name'      => $files['name'][$key],
                        'type'      => $files['type'][$key], 
                        'tmp_name'  => $files['tmp_name'][$key], 
                        'error'     => $files['error'][$key],
                        'size'      => $files['size'][$key]
                    ); 
                    $_FILES = array ($file_key => $file); 
                    foreach ($_FILES as $file => $array) {
                        $attach_id = '';
                        if($this->validate_image_upload($file)) {
                            $attach_id = $this->handle_attachment($file, $post_id);
                        } else {
                            $error[] =  'Invalid Image Type';
                            $this->notification = $error;                     
                        }                              
                        $attach_ids[]      = $attach_id;        
                    }
                #} 
            } 
        } 
        return $attach_ids;       
    } //wp_upload_multifile

    function handle_attachment($file_handler,$post_id,$set_thu=false) {

        // check to make sure its a successful upload
        if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attach_id = media_handle_upload( $file_handler, $post_id );
        if ( is_numeric( $attach_id ) ) {
            //update_post_meta( $post_id, '_my_file_upload', $attach_id );
            return $attach_id;
        }
        return false;
    } //handle_attachment
    
    function register_business_featured_image($post_id, $file, $input_name){ 
        if(!$post_id) return false;

        $attach_id = $this->wp_upload_file($post_id, $file, $input_name);
        if($attach_id) {
            set_post_thumbnail( $post_id, $attach_id );
        } else {
            return false;
        }                
    } // register_business_featured_image()

    function formatschedule($biz_days){
        $days = '---';
        if($biz_days) {
            if(count($biz_days) == 7) {
                $days = 'Monday - Sunday';
            } else if(count($biz_days) == 5) {
                $daylist = array('Monday', 
                              'Tuesday', 
                              'Wednesday', 
                              'Thursday', 
                              'Friday'
                        );   
                if(serialize($daylist) == serialize($biz_days)) {
                    $days = 'Monday - Friday';
                } else {
                    $days = implode(',', $biz_days);    
                }
            } else {
                $days = implode(',', $biz_days);
            }
        }     
        return $days;   
    }
    function auto_login( $user_id, $redirect_to = '' ) {
        #$redirect_to = $redirect_to ? : get_bloginfo( 'url' );
        wp_set_auth_cookie( $user_id, false, is_ssl() );
        if($redirect_to) { 
            wp_redirect( $redirect_to ); exit; 
        }
    } // auto_login
    
    function get_user_billing($user_id=''){
        global $fz_helper;
        $user_id = $user_id ?: get_current_user_id();

        $user = array();
        $user['ID'] = $user_id;

        $fields = acf_get_fields($this->billing_field_id);
        foreach ($fields as $row) {
            $key = $row['name'];
            $user[$key] = get_field($key, 'user_'.$user_id);
        }
        return $user;
    }

    function get_vendor($user_id='') {
        $user_id = $user_id ?: get_current_user_id();
        $args = array(
            'post_type'         => 'business',
            'author'            =>  $user_id, // I could also use $user_ID, right?
            'orderby'           =>  'post_date',
            'order'             =>  'ASC',
            'post_status'       =>  'any',
            'posts_per_page'    => 1
            );

        // get his posts 'ASC'
        $user_posts = get_posts( $args );             
        foreach ( $user_posts as $post ) {
            return $post;
        }
        return false;
    } //get_vendor

    function get_vendor_data($user_id='', $post= array()) {
        $user_id = $user_id ?: get_current_user_id();
        
        $post = $post ? $post : $this->get_vendor($user_id);
        $postdata = array();        
        if($post) {
            $post_id = $post->ID;
            $postdata['ID']                     = $post_id;
            
            $user_info = get_userdata($user_id);
            $postdata['user']['name']   = $user_info->first_name." ".$user_info->last_name;
            $postdata['user']['email']  = $user_info->user_email;

            $post_id  = $post->ID;            
            $postdata['business']['name']       = $post->post_title;
            $postdata['business']['content']    = $post->post_content;

            $fields = acf_get_fields($this->vendor_field_id); # FIELD Business/Vendor Field
            foreach ($fields as $row) {
                $key        = $row['name'];
                $meta_key   = $row['key'];
                $postdata['business'][$key] = get_field( $meta_key, $post_id);            
            
            }            

            $fields = acf_get_fields($this->vendor_product_field_id); # FIELD Business/Vendor Field
            foreach ($fields as $row) {
                $key        = $row['name'];
                $meta_key   = $row['key'];
                $postdata['business'][$key] = get_field( $meta_key, $post_id);            
            
            }  

            $fields = acf_get_fields($this->vendor_location_field_id); # FIELD location
            foreach ($fields as $row) {
                $key        = $row['name'];
                $meta_key   = $row['key'];
                $postdata['business'][$key] = get_field( $meta_key, $post_id);            
            
            } 

            $category = wp_get_post_terms($post_id, 'business-type', array("fields" => "all"));
            $category_fixed = array();
            foreach ($category as $term) {
                $category_fixed[$term->slug] = $term;
            }
            $postdata['business']['category'] = $category_fixed;
            
            $postdata['post'] = $post;
        }

        return $postdata;
    }//get_vendor_data

    function get_post_files($post_id) {

        $haystack = '-' . $content;

            $args = array (
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'post_parent' => $post_id,
                'order_by' => 'date',
                'order' => 'ASC'
            );
            $loop = new WP_Query($args);

            if ($loop->have_posts()){
                $content = $content . ' <h5>List of uploaded files:</h5>';
                while ($loop->have_posts()){
                    $loop->the_post();
                    #$modify = get_edit_post_link();
                    $content = $content . ' <div class="col-sm-3">
                                                <div class="well">
                                                    <img src="' . wp_get_attachment_url( $loop->post->ID ) . '" title="' . get_the_title() . '" class="img-responsive" />
                                                </div>
                                            </div>';

                }
                wp_reset_postdata();
            }
        return $content;
    }
    function is_vendor_valid( $date_start, $date_end, $redirect=true ) {
        if(!$date_start || !$date_end) {
            $valid = false;
        } else {
            $date_now = strtotime(date("Y-m-d"));
            $date_end = strtotime($date_end);        

            if($date_now >= $date_end) {
                $valid = false;
            } else {
                $valid = true;
            }       
        }
        
        if(!$valid) {
            if($redirect){
                page_404();   
            } else {
                return false;
            }
        }        
        return true;
    }//is_vendor_valid
    
    function format_date_subscription($date_start, $date_end){
        if($date_start && $date_end) {
            return date("F j, Y", strtotime($date_start)).' to '.date("F j, Y", strtotime($date_end));
        } else {
            return 'Pending';
        }
    }// format_date_subscription()

    function is_paid_vendor($user_id=''){
        $user_id = $user_id ?: get_current_user_id();

        $user = get_userdata( $user_id );
        if ( in_array( $this->vendor_role, (array) $user->roles ) ) {
            return true;
        }

        return false;

    }//is_paid_vendor

    function is_vendor($user_id='') {
        $user_id = $user_id ?: get_current_user_id();
        
        if(!$user_id) return false;

        $post = $this->get_vendor($user_id);
        if($post) {
            if($post->post_status == 'pending') {
                $error      = array();
                $error[]    = 'Unpaid business registration. <a href="'.$this->checkout_url.'">Goto Checkout</a>';
                $this->notification = $error;                 
                return 'unpaid';
            } else if($post->post_status == 'publish') {
                $error      = array();
                $error[]    = 'Account already registered a business.  <a href="'.$this->profile_url.'">View Profile</a>';
                $this->notification = $error;                 
                return 'paid';
            }
        }
        return false;
    } //is_vendor

    function checkout(){
        #validate if user is already vendor
        // check if login user
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $status = $this->is_vendor($user_id);
            
            $this->vendor_status = $status;
            if($status == false) {         
                wp_redirect( $this->biz_url ); exit;
            } else if($status == 'paid') {                                
                $this->display_error();
            }          
        } else {
            wp_redirect( get_bloginfo( 'url' ).'/login' ); exit;            
        }      
        
    } //checkout()

    function wp_new_user_notificationV2( $user_id, $password) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
        
        $message .= sprintf(__('Email: %s'), $user_email) . "\r\n";
        $message .= sprintf(__('Password: %s'), $password) . "\r\n";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);


        $message  = __('Hi there,') . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";
        $message .= sprintf(__('Email: %s'), $user_email) . "\r\n";
        $message .= sprintf(__('Password: %s'), $password) . "\r\n\r\n";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __(get_option('blogname'));

        wp_mail($user_email, sprintf(__('[%s] Your Account Info'), get_option('blogname')), $message);

    } //wp_new_user_notificationV2

    function wp_deactivate_account_notification( $user_id, $post_data) {

        $post_data = $this->get_vendor_data($user_id, $post_data);
        $package = $this->get_package($post_data['business']['packages']);

        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $date_start = $post_data['business']['date_start'];
        $date_end   = $post_data['business']['date_end'];     
        $biz_date   = ($date_start && $date_end) ? date("F j, Y", strtotime($date_start)).' to '.date("F j, Y", strtotime($date_end)) : '<i>Pending</i>';       


        $message  = __('Hi there,') . "\r\n\r\n";
        $message .= sprintf(__("Your business profile on %s is already expired."), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";

        $message .= sprintf(__('Business Name: %s'), $post_data['business']['name']) . "\r\n\r\n";
        $message .= sprintf(__('Package: %s'), $package['title']) . "\r\n\r\n";
        $message .= sprintf(__('Date: %s'), $biz_date) . "\r\n\r\n";
        $message .= sprintf(__('Email: %s'), $user_email) . "\r\n\r\n";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __(get_option('blogname'));
        
        wp_mail($user_email, sprintf(__('[%s] Business Profile Deactivated'), get_option('blogname')), $message);

    } //wp_new_user_notificationV2

    function validation_billing($post_data) {
        $fields_require = array(
                                'billing_first_name'    => 'Please enter your first name',
                                'billing_last_name'     => 'Please enter your last name',
                                'billing_address'       => 'Please enter your address',
                                'billing_city'          => 'Please enter your city',
                                'billing_country'       => 'Please enter your country',
                                'billing_state'         => 'Please enter your state',
                                'billing_post_code'     => 'Please enter your post code',
                            );
        $error = array();        
        foreach($post_data as $key => $post) {

            if (isset($fields_require[$key])) {
                if($post == '') {
                    $error[] = $fields_require[$key];
                }
            }          
        } 
        
        if($error) {
            return $error;
        }
        
        return false;
    } //validation_billing

    function validation_information($postdata) {
        $fields_require = array(
                                'user' => array
                                (
                                    'first_name'    => 'Please enter your first name',
                                    'last_name'     => 'Please enter your last name',
                                    'email'         => 'Please enter your email address',
                                )
                            );
        $error = array();
        $post_user  = $postdata['user'];            
        foreach($post_user as $key => $post) {

            if (isset($fields_require['user'][$key])) {
                if($post == '') {
                    $error[] = $fields_require['user'][$key];
                }
            }          
        }

        if(isset($post_user['email']) && $post_user['email']) {
            $email    = $post_user['email'];
            $username = $post_user['email'];            
            if( !is_email($email) ) {
                $error[] =  'Email address is invalid.';
            } else if ($email != $postdata['confirm_email'] && email_exists($email) ) {
                $error[] =  'Email address is already registered.';
            }
        }
        if( isset($post_user['pass1']) && $post_user['pass1'] ) {
            if( strlen($post_user['pass1']) < 6) {
                $error[] =  'Your password must be at least 6 characters in length';            
            } else if( $post_user['pass1'] != $post_user['pass2'] ) {
                $error[] =  'Password not match';
            }            
        }

        return $error ? $error :  false;
    } //validation_information

    function validate_business($postdata, $error){
        $fields_require = array(
                                'business' => array
                                (
                                    'name'        => 'Please enter the business name',
                                    'contactno'   => 'Please enter the contact number',
                                    //'address'     => 'Please enter the address',
                                    'website'     => 'Please enter the website url',
                                    'content'     => 'Please enter a description'
                                )   
                            );             
        foreach($postdata as $key => $post) {

            if (isset($fields_require['business'][$key])) {
                if($post == '') {
                    $error[] = $fields_require['business'][$key];
                }
            }          
        }        

        return $error;                      
    }//validate_business

    function validation($postdata){

        $fields_require = array(
                                'user' => array
                                (
                                    'name'    => 'Please enter your name',
                                    'email'   => 'Please enter your email address',
                                    //'pass1'   => 'Please enter your password',
                                    //'pass2'   => 'Please enter your password twice',
                                ),

                                'business' => array
                                (
                                    'name'        => 'Please enter the business name',
                                    'contactno'   => 'Please enter the contact number',
                                    //'address'     => 'Please enter the address',
                                    'website'     => 'Please enter the website url',
                                    'content'     => 'Please enter a description'
                                )
                            );
        $error = array();
        $post_user  = $postdata['user'];            
        foreach($post_user as $key => $post) {

            if (isset($fields_require['user'][$key])) {
                if($post == '') {
                    $error[] = $fields_require['user'][$key];
                }
            }          
        }

        if(isset($post_user['email']) && $post_user['email']) {
            $email    = $post_user['email'];
            $username = $post_user['email'];            
            if( !is_email($email) ) {
                $error[] =  'Email address is invalid.';
            } else if ( email_exists($email) ) {
                $error[] =  'Email address is already registered.';
            }
        }
        if( isset($post_user['pass1']) && 
            isset($post_user['pass2']) && 
            $post_user['pass1'] &&
            $post_user['pass2']
            ) {
            if( strlen($post_user['pass1']) < 6) {
                $error[] =  'Your password must be at least 6 characters in length';            
            } else if( $post_user['pass1'] != $post_user['pass2'] ) {
                $error[] =  'Password not match';
            }
        }
        
        $error = $this->validate_business($postdata['business'], $error);

        #post_exists
        return $error ? $error :  false;        
    } //validation()

    function redirect_notification($redirect_url){
        if($this->notification) {
            $_SESSION["process_notification"]   = $this->notification;
            $_SESSION["process_success"]        = $this->success;
        }
        wp_redirect( $redirect_url ); exit;
    } //redirect_notification
    
    function register_my_session()
    {
      if( !session_id() )
      {
        session_start();
      }
    }    
    function session_notification(){
        if(isset($_SESSION["process_notification"])) {
            $this->notification = $_SESSION["process_notification"];            
            $this->success      = $_SESSION["process_success"];
            UNSET($_SESSION["process_notification"]);            
            UNSET($_SESSION["process_success"]);
            $this->display_error();
        }
    }

    function add_notification($error){
        $err = array();
        foreach ($this->notification as $key => $value) {
            $err[] = $value;
        }
        foreach ($error as $key => $value) {
            $err[] = $value;
        }        
    } //add_notification

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
                if ( (strpos($value, 'ERROR') !== false) || (strpos($value, 'Success') !== false) ) {
                    $html .= "$newline $value."; $newline = '<br>';
                } else {
                    $html .= "$newline<strong>$label</strong>: $value."; $newline = '<br>';
                }
            }            
            echo $html ? '
            <div class="alert alert-'.$class.' fade in ">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                '.$html.'
            </div>' : '';
        });        
    } //display_error

    function get_acf_key($field_name){
        global $wpdb;

        return $wpdb->get_var("
            SELECT post_name
            FROM $wpdb->posts
            WHERE post_type='acf-field' AND post_excerpt='$field_name';
        ");
    } //get_acf_key

    public function registration_form(){
        ob_start();
        get_template_part('templates/profile/form-registration-business');
        return ob_get_clean();
    } // registration_form()

} // sac_template    

if(! is_admin()) $profiler = new sac_profiler();

/*create new user role*/
function createRole() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
    
    $args = array(
                'read'      => 1,
                'level_0'   => 1,
                'upload_files' => true
            );  
    //Adding a 'new_role' with all admin caps
    $wp_roles->add_role('vendor', 'Vendor', $args);
} //createRole
#add_action('init', 'createRole');
$user_role = 'vendor'; // Change user role here
$contributor = get_role($user_role);
$contributor->add_cap('upload_files');
/*create new user role*/

function checkvendorstatus () {
    $bz_renew = new sac_profiler_renew();
    $bz_renew->checkvendorstatus();
} 

add_action('check_vendor_subscription', 'checkvendorstatus'); 

//Manage Your Media Only
function users_my_media_only( $wp_query ) {
    if ( false !== strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/admin-ajax.php' ) ) {
        $current_user = wp_get_current_user();
        $current_user = $current_user->ID;
        if ( ! current_user_can( 'manage_options' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}
add_filter('parse_query', 'users_my_media_only' );

function add_js_editor(){
    wp_enqueue_media();
    /*wp_enqueue_script( 'tiny_mce' );
    if (function_exists('wp_tiny_mce')) wp_tiny_mce();*/    
}
?>