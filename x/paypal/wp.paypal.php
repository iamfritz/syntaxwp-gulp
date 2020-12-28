<?php
/**
* manage paypal account
*/

class fz_paypal {

    var $notification;

    function __construct() {
    
        #$this->set_variable();
        add_action('admin_menu', [$this, 'create_menu']);                        
    }

    function create_menu() {

        //create new top-level menu
        add_menu_page('Paypal', 'Paypal Settings', 'administrator', __FILE__, [$this, 'settings_page'] , ASSETS_IMG . 'paypal-icon.png', 22 );

        //call register settings function
        add_action( 'admin_init', [$this, 'register_settings_fields'] );
    } //create_menu

    function register_settings_fields() {
        //register our settings
        register_setting( 'paypal-settings', 'paypal_sandbox' );
        register_setting( 'paypal-settings', 'paypal_email' );
    } //register_settings_fields

    function settings_page() {
        include_once('view/settings.php');
    } //settings_page

} // fz_paypal    

if(is_admin()) $fz_paypal = new fz_paypal();
?>
