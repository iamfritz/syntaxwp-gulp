<?php
define('CRON_LOG_FILE', dirname( __FILE__ ).'/cron.log');

class sac_profiler_renew {

    function __construct() {
        add_action('admin_menu', array($this, 'add_submenu') );
    }
    function add_submenu(){
        add_submenu_page('edit.php?post_type=business', 'Pending Subscription', 'Pending Subscription', 'manage_options','user-subscription', array($this, 'user_subscription'));  
    } //add_submenu
    
    function user_subscription(){
        $posts = $this->get_all_published_vendor();  
    }

    function get_all_published_vendor() {
        if(isset($_GET['s_action']) && $_GET['s_action'] == 'checksubscripton') {
            $this->checkvendorstatus();

        }
        

        $args = array(
            'post_type'         => 'business',
            'orderby'           =>  'post_date',
            'order'             =>  'ASC',
            'post_status'       =>  'pending',
            'posts_per_page'    => -1
            );        
        $expired_list = get_posts( $args );

        include('views/expired-list.php');
            
    } //get_all_published_vendor

    function checkvendorstatus(){
        $args = array(
            'post_type'         => 'business',
            'orderby'           =>  'post_date',
            'order'             =>  'ASC',
            'post_status'       =>  'publish',
            'posts_per_page'    => -1
            );

        $profiler = new sac_profiler();

        $expired_list = array();
        // get his posts 'ASC'
        $posts = get_posts( $args );                     
        foreach ( $posts as $post ) {
            $change_status = false;
            $post_id    = $post->ID;
            $date_start = get_field('date_start', $post_id );
            $date_end   = get_field('date_end', $post_id );

            if(!$profiler->is_vendor_valid( $date_start, $date_end, false)) {
                $change_status = true;
                $expired_list[] = $post;
            }

            if($change_status) {
                $entry = $post;               
                $profiler->wp_deactivate_account_notification( $post->post_author, $entry);
                // Update post
                $new_post                   = array();
                $new_post['ID']             = $post_id;
                $new_post['post_status']    = 'pending';
                // Update the post into the database
                wp_update_post( $new_post );   
            }
        }    
        $this->write_to_log(count($expired_list));
        /*if($expired_list) {
            echo '<div class="updated" style=""><p>'.count($expired_list).' business is now pending. Email Notification sent to the vendor.</p></div>';
        }    */
    }//checkvendorstatus

    function write_to_log($count=0) {            

        $message = "$count business vendor has been deactivated. [".date("F j, Y")."]";
        
        // Write to log
        $fp = fopen ( CRON_LOG_FILE , 'a' ) or die("Unable to open file!");
        fwrite ( $fp, $message . "\n" );
        fclose ( $fp ); // close file
        #chmod ( LOG_FILE , 0600 );
    }
}

if(is_admin()) $bz_renew = new sac_profiler_renew();

#$bz_renew->get_all_published_vendor();