<?php
//hooks

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
}
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
*/
/*******CF7 hook END***********************************************************/


