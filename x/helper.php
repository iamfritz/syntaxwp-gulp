<?php

# prefix fo class and variable
if (!class_exists('fz_helper')) {

	class fz_helper {

		var $country_json;
		var $au_state_json;
	  
	   function __construct() {
  	        $return = '';
			$this->country_json		= $this->exportcountries();
			$this->au_state_json	= $this->exportaustates();  	        
	    }

	    public function pr($arr= '', $print = false){		
	    	if(is_array($arr) ) {
	    		$ret = print_r($arr,true);
	    	} else {
	    		$ret = $arr;
	    	}
	    	$ret = "<pre>$ret</pre>";     	
	    	if($print) {
	    		return $ret;
	    	} else {
	    		echo $ret;
	    	}
	 
		} //prefix		

		/*ACF get field*/

		function get_acf_key($field_name){
		    global $wpdb;

		    return $wpdb->get_var("
		        SELECT post_name
		        FROM $wpdb->posts
		        WHERE post_type='acf-field' AND post_excerpt='$field_name';
		    ");
		} //get_acf_key

		function set_field_value($key, $postdata){
			if(!$postdata) return;

			$keys = explode(',', $key);

			$value = '';
			foreach ($keys as $key) {
				$value = $value ? $value[$key] : $postdata[$key];
				#$value = $value ? isset($value[$key]) : (isset($postdata[$key]) ? $postdata[$key] : '');
			}
			echo $value;			
		} //set_field_value

		function my_acf_get_fields_in_group( $group_id ) {
			$acf_fields = acf_get_fields($group_id);
		    return $acf_fields;
		}		
		function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
		    global $wpdb;
		    if( empty( $key ) )
		        return;
		    $r = $wpdb->get_results( $wpdb->prepare( "
		        SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} pm
		        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		        WHERE pm.meta_key = '%s' 
		        AND p.post_status = '%s' 
		        AND p.post_type = '%s'
		    ", $key, $status, $type ));

		    foreach ( $r as $my_r )
		        $metas[$my_r->ID] = $my_r->meta_value;

		    return $metas;
		}

		function get_location_geo($loc){
	        // Get JSON results from this request
	        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($loc).'&sensor=false');
	        
	        // Convert the JSON to an array
	        $geo = json_decode($geo, true);
	        
	        $location = array();
	        if ($geo['status'] == 'OK') {
	        	$gmap = $geo['results'][0];
	          	// Get Lat & Long
	          	$location['address'] 	= $gmap['formatted_address'];
	          	$location['lat'] 		= $gmap['geometry']['location']['lat'];
	          	$location['lng'] 		= $gmap['geometry']['location']['lng'];
	        }	
	        return $location;
		}

		function exportcountries($code = '') {
		  $results = array();

		  $contents   = file_get_contents(get_template_directory() . '/libraries/countries.json');
		  if($contents) {
		    $contents   = utf8_encode($contents);
		    
		    $results    = json_decode($contents); 
		    if($code) {
		      if(isset($results[$code])) {
		        $results = $results[$code];
		      } else {
		        $results = '';
		      }
		    }  
		  }

		  return $results;
		}
		function getcountrylist_dropdown($selected = 'Australia') {
		  $select = '';
		  $countries = $this->country_json;
		  foreach ($countries as $key => $value) {
		    $select .= "<option value='$value' ".($selected == $value ? 'SELECTED' : '').">$value</option>";
		  }
		  echo $select;
		}

        function exportaustates($code = '') {
          $results = array();
 
          $contents   = file_get_contents(get_template_directory() . '/libraries/au-states.json');
          #$contents   = file_get_contents('au-states.json');
          if($contents) {
            $contents   = utf8_encode($contents);
             
            $results    = json_decode($contents); 
            if($code) {
              if(isset($results[$code])) {
                $results = $results[$code];
              } else {
                $results = '';
              }
            }  
          }
 
          return $results;
        }
        function getaustatelist_dropdown($selected = '') {
          $select = '';
          $countries = $this->au_state_json;
          foreach ($countries as $key => $row) { 
            $state = ucwords($row->name);
            $select .= "<option value='$state' ".($selected == $state ? 'SELECTED' : '').">$state</option>";
          }
          echo $select;
        }

	}

	$fz_helper = new fz_helper();
}

?>
