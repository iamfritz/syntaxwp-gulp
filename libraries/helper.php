<?php
/**
 * Debugging tools
 *
 */

/**
 * Pretty printing debugging tool
 */
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