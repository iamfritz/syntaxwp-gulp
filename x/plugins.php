<?php
/*
Plugin Name: Advanced Custom Fields Pro
Plugin URI: http://www.advancedcustomfields.com/
Description: Fully customise WordPress edit screens with powerful fields. Boasting a professional interface and a powerful API, it’s a must have for any web developer working with WordPress. Field types include: Wysiwyg, text, textarea, image, file, select, checkbox, page link, post object, date picker, color picker, repeater, flexible content, gallery and more!
Version: 5.0.9
Author: elliot condon
Author URI: http://www.elliotcondon.com/
Copyright: Elliot Condon
Text Domain: acf
Domain Path: /lang
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


#check plugin needed
if( LOAD_ACF_THEME_PLUGIN && !function_exists( 'the_field' ) ) {
    
    #add_action( 'admin_notices', array($this, 'my_acf_notice') );

    // 1. customize ACF path
    add_filter('acf/settings/path', 'my_acf_settings_path');
 

    // 2. customize ACF dir
    add_filter('acf/settings/dir', 'my_acf_settings_dir');

    // 4. Include ACF
    include_once( get_stylesheet_directory() . '/libraries/plugins/advanced-custom-fields-pro/acf.php' );

    // 3. Hide ACF field group menu item
    if(HIDE_ACF) {
        add_filter('acf/settings/show_admin', '__return_false');
    }

    function custom_acf_repeater_colors() {
       echo '<style type="text/css">
            .acf-input .acf-repeater .acf-table .acf-row:nth-child(even){
                border-left: 2px solid #72777c;
            }
            .acf-input .acf-repeater .acf-table .acf-row:nth-child(odd){
                
            }                        
             </style>';
    }
    add_action('admin_head', 'custom_acf_repeater_colors');

    function my_acf_init() {
        
        acf_update_setting('google_api_key', ACF_GOOGLEMAP_API);
    }

    add_action('acf/init', 'my_acf_init');

} //if( !function_exists( 'the_field' ) ) {

function my_acf_notice() {
  ?>
  <div class="update-nag notice">
      <span><?php _e( 'Please install Advanced Custom Fields, it is required for this plugin to work properly!', 'my_plugin_textdomain' ); ?></span>
  </div>
  <?php
} //my_acf_notice

function my_acf_settings_path( $path ) {
 
    // update path
    $path = get_stylesheet_directory() . '/libraries/plugins/advanced-custom-fields-pro/';
    
    // return
    return $path;
    
}
 
function my_acf_settings_dir( $dir ) {
 
    // update path
    $dir = get_stylesheet_directory_uri() . '/libraries/plugins/advanced-custom-fields-pro/';
    
    // return
    return $dir;
    
}

/* Registers the theme option menus for the Advanced Custom Fields plugin. */
# export fields
# use acf-php-recovery if you want to edit and add missing fields

add_action( 'after_setup_theme', 'acf_export_theme_fields' );

function acf_export_theme_fields(){
    # ACF option page
    if( function_exists('acf_add_options_page') ) {
        
        acf_add_options_page(array(
            'page_title'    => 'Theme General Settings',
            'menu_title'    => 'Theme Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-admin-appearance',
            'position'      => 61
        ));
        
        /*acf_add_options_sub_page(array(
            'page_title'    => 'Banner Slider',
            'menu_title'    => 'Banner Slider',
            'parent_slug'   => 'theme-general-settings',
        ));*/
        acf_add_options_sub_page(array(
            'page_title'    => 'Page Global Section',
            'menu_title'    => 'Page Global Section',
            'parent_slug'   => 'theme-general-settings',
        ));
        acf_add_options_sub_page(array(
            'page_title'    => 'Social Feeds',
            'menu_title'    => 'Social Feeds',
            'parent_slug'   => 'theme-general-settings',
        ));        
    }    
    
    /*
        export option fields
    */
    if(DEFAULT_ACF_FIELDS && function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array (
            'key' => 'group_55b397c2c25e0',
            'title' => 'Banner Slider',
            'fields' => array (
                array (
                    'key' => 'field_55b397f577c8d',
                    'label' => 'Banner',
                    'name' => 'banner',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'min' => '',
                    'max' => '',
                    'layout' => 'row',
                    'button_label' => 'Add New Banner',
                    'collapsed' => '',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_55b3982877c8e',
                            'label' => 'Image',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => 0,
                            'min_height' => 0,
                            'min_size' => 0,
                            'max_width' => 0,
                            'max_height' => 0,
                            'max_size' => 0,
                            'mime_types' => '',
                        ),
                        array (
                            'key' => 'field_55b3984177c8f',
                            'label' => 'Title',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_55b3984b77c90',
                            'label' => 'Content',
                            'name' => 'content',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'maxlength' => '',
                            'rows' => '',
                            'new_lines' => 'wpautop',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_55b3987377c92',
                            'label' => 'Button Label',
                            'name' => 'button_label',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_55b3985f77c91',
                            'label' => 'Button URL',
                            'name' => 'button_url',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_55b3989677c93',
                            'label' => 'OR',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'esc_html' => 0,
                            'new_lines' => 'wpautop',
                        ),
                        array (
                            'key' => 'field_55b398a477c94',
                            'label' => 'Page URL',
                            'name' => 'page_url',
                            'type' => 'page_link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array (
                            ),
                            'taxonomy' => array (
                            ),
                            'allow_null' => 1,
                            'multiple' => 0,
                        ),
                        array (
                            'key' => 'field_55b398e977c95',
                            'label' => '---------------------------------',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'esc_html' => 0,
                            'new_lines' => 'wpautop',
                        ),
                    ),
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-banner-slider',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));

        acf_add_local_field_group(array (
            'key' => 'group_56983578714ea',
            'title' => 'Contact Us',
            'fields' => array (
                array (
                    'key' => 'field_5698358b0728d',
                    'label' => 'Maps',
                    'name' => 'maps',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'min' => '',
                    'max' => '',
                    'layout' => 'block',
                    'button_label' => 'Add Map',
                    'collapsed' => '',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_56984bde798b0',
                            'label' => 'Name',
                            'name' => 'name',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_56984bec798b1',
                            'label' => 'Contact Number',
                            'name' => 'contact_number',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array (
                            'key' => 'field_56984bec798bb',
                            'label' => 'Fax',
                            'name' => 'fax',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),                        
                        /*array (
                            'key' => 'field_56984bf7798b2',
                            'label' => 'Contact Email',
                            'name' => 'contact_email',
                            'type' => 'email',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '25',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),*/
                        array (
                            'key' => 'field_569835a30728e',
                            'label' => 'Maps',
                            'name' => 'maps',
                            'type' => 'google_map',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'center_lat' => '',
                            'center_lng' => '',
                            'zoom' => '',
                            'height' => '',
                        ),
                    ),
                ),
                array (
                    'key' => 'field_56a6b3dc84167',
                    'label' => 'Contact Form',
                    'name' => 'contact_form',
                    'type' => 'text',
                    'instructions' => 'Paste here the short code of the form to be used.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'allow_null' => 1,
                    'multiple' => 0,
                    'disable' => array (
                        0 => 1,
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'templates/template-page-contact-us.php',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));

        acf_add_local_field_group(array (
            'key' => 'group_5480f53a0ec49',
            'title' => 'Theme General Settings',
            'fields' => array (
                array (
                    'key' => 'field_55e4eabd4e618',
                    'label' => 'Fav Icon',
                    'name' => 'fav_icon',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => 50,
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'url',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array (
                    'key' => 'field_56bf4ab60d2aa',
                    'label' => 'Logo',
                    'name' => 'logo',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => 50,
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array (
                    'key' => 'field_5480f68f23154',
                    'label' => 'Social Account',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array (
                    'key' => 'field_55bacfada0c33',
                    'label' => 'Social Account',
                    'name' => 'social_account',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'min' => '',
                    'max' => '',
                    'layout' => 'table',
                    'button_label' => 'Add Account',
                    'collapsed' => '',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_55bacfcea0c35',
                            'label' => 'Account',
                            'name' => 'account',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array (
                                'facebook' => 'Facebook',
                                'twitter' => 'Twitter',
                                'google' => 'Google',
                                'linkedin' => 'Linkedlin',
                                'youtube' => 'Youtube',
                                'pinterest' => 'Pinterest',
                                'instagram' => 'Instagram',
                            ),
                            'default_value' => array (
                            ),
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'ajax' => 0,
                            'placeholder' => '',
                            'disabled' => 0,
                            'readonly' => 0,
                        ),
                        array (
                            'key' => 'field_55bad08f454db',
                            'label' => 'URL',
                            'name' => 'url',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                    ),
                ),
                array (
                    'key' => 'field_55e4eb3a7b89c',
                    'label' => 'Contact Details',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array (
                    'key' => 'field_55e4eb527b89d',
                    'label' => 'Contact Email',
                    'name' => 'contact_email',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array (
                    'key' => 'field_55e4eb7619e18',
                    'label' => 'Contact Number',
                    'name' => 'contact_number',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                array (
                    'key' => 'field_55e4eb7619e88',
                    'label' => 'Fax',
                    'name' => 'fax',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),                
                array (
                    'key' => 'field_5480f6c485f1a',
                    'label' => 'Footer Information',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array (
                    'key' => 'field_5480f6da85f1c',
                    'label' => 'Footer Title',
                    'name' => 'footer_title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                array (
                    'key' => 'field_5480f6da85f1b',
                    'label' => 'Footer Info',
                    'name' => 'footer_info',
                    'type' => 'text',
                    'instructions' => 'Add {Y} for current year, {SITENAME} for Sitename',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '© {Y} {SITENAME}. ALL RIGHTS RESERVED',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                array (
                    'key' => 'field_55e4ead14e619',
                    'label' => 'Google Analytics',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array (
                    'key' => 'field_55e4eae74e61a',
                    'label' => 'Analytics Script',
                    'name' => 'analytics_script',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
                array (
                    'key' => 'field_573f436b4fd62',
                    'label' => 'Developer',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array (
                    'key' => 'field_573f43784fd63',
                    'label' => 'Hide ACF Menu',
                    'name' => 'hide_acf_menu',
                    'type' => 'true_false',
                    'instructions' => 'Don\'t change unless you know what your doing.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => 'Hide Advanced Custom Field Menu (Custom Fields)',
                    'default_value' => 0,
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-general-settings',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));

    endif;

} //acf_export_theme_fields

include('plugins/list-custom-taxonomy-widget.php');
?>