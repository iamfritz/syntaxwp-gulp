<?php
/* add_action('init', 'calendar_register');

function calendar_register() {
    $labels = array(
        'menu_name' => _x('Calendar', 'calendar'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Calendar',
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('calendar', $args);
}	 */

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
  $post_feature = get_post_list();	
  
  $meta_box['news'] = array(
    'type' => 'post',  
	'id' => 'news_fields',  
    'title' => 'News Boxes',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Message',
            'desc' => 'Member Message',
            'id' => 'message',
            'type' => 'text',
            'default' => ''
        )
    )
  );
  
  $meta_box['page_aboutus'] = array(
    'type' => 'page',  
	'id' => 'programs_fields',  
    'title' => 'Program Business Boxes',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Total Entry on slider',
            'desc' => 'Empty to display all',
            'id' => 'per_slide',
            'type' => 'text',
            'default' => ''
        ),     
		array(
            'name' => 'Navy Box Slide',
            'desc' => 'Activate slides by selecting category.',
            'id' => 'image_navybox_slide',
            'type' => 'category',
            'default' => ''
        ), 
		array(
            'name' 		=> 'Navy Box Page Preview',
            'desc' 		=> 'Activate it by selecting the page/post to display.',
            'id' 		=> 'navybox_page',
            'type' 		=> 'select',
			'options'	=> $post_feature,
            'default' 	=> ''			
        ), 
		array(
            'name' 		=> 'Navy Box Page Image',
			'desc' 		=> 'Check to display thumbnail image',
            'id' 		=> 'navybox_page_thumb',
            'type' 		=> 'checkbox',
            'default' 	=> ''			
        ), 		
        array(
            'name' => 'Navy Box Image',
            'desc' => 'Second Image Box',
            'id' => 'image_navybox',
            'type' => 'wp_editor',
            'std' => ''
        ),		
		array(
            'name' => 'Orange Box Slide',
            'desc' => 'Activate slides by selecting category.',
            'id' => 'image_orangebox_slide',
            'type' => 'category',
            'default' => ''
        ), 		
		array(
            'name' 		=> 'Orange Box Page Preview',
            'desc' 		=> 'Activate it by selecting the page/post to display.',
            'id' 		=> 'orangebox_page',
            'type' 		=> 'select',
			'options'	=> $post_feature,
            'default' 	=> ''			
        ), 		
		array(
            'name' 		=> 'Orange Box Page Image',
			'desc' 		=> 'Check to display thumbnail image',
            'id' 		=> 'orangebox_page_thumb',
            'type' 		=> 'checkbox',
            'default' 	=> ''			
        ), 			
        array(
            'name' => 'Orange Box Image',
            'desc' => 'First Image Box',
            'id' => 'image_orangebox',
            'type' => 'wp_editor',
            'std' => ''
        ),
		array(
            'name' => 'Yellow Box Slide',
            'desc' => 'Activate slides by selecting category.',
            'id' => 'image_yellowbox_slide',
            'type' => 'category',
            'default' => ''
        ), 		
		array(
            'name' 		=> 'Yellow Box Page Preview',
            'desc' 		=> 'Activate it by selecting the page/post to display.',
            'id' 		=> 'yellowbox_page',
            'type' 		=> 'select',
			'options'	=> $post_feature,
            'default' 	=> ''			
        ), 		
		array(
            'name' 		=> 'Yellow Box Page Image',
			'desc' 		=> 'Check to display thumbnail image',
            'id' 		=> 'yellowbox_page_thumb',
            'type' 		=> 'checkbox',
            'default' 	=> ''			
        ), 			
        array(
            'name' => 'Yellow Image Box',
            'desc' => 'Yellow Image Box',
            'id' => 'image_yellowbox',
            'type' => 'wp_editor',
            'std' => ''
        ),	
		array(
            'name' => 'Blue Box Slide',
            'desc' => 'Activate slides by selecting category.',
            'id' => 'image_bluebox_slide',
            'type' => 'category',
            'default' => ''
        ), 		
		array(
            'name' 		=> 'Blue Box Page Preview',
            'desc' 		=> 'Activate it by selecting the page/post to display.',
            'id' 		=> 'bluebox_page',
            'type' 		=> 'select',
			'options'	=> $post_feature,
            'default' 	=> ''			
        ), 		
		array(
            'name' 		=> 'Blue Box Page Image',
			'desc' 		=> 'Check to display thumbnail image',
            'id' 		=> 'bluebox_page_thumb',
            'type' 		=> 'checkbox',
            'default' 	=> ''			
        ), 		
        array(
            'name' => 'Blue Image Box',
            'desc' => 'Blue Image Box',
            'id' => 'image_bluebox',
            'type' => 'wp_editor',
            'std' => ''
        ),
        array(
            'name' => 'Bottom Image',
            'desc' => 'About us bottom part content image',
            'id' => 'about_bottom_image',
            'type' => 'imagefile',
            'std' => ''
        )		
    )
);

$custom_page = array('page_aboutus'=>'page-about-us.php', 'news'=>'post-news');

function get_meta_option(){
	global $meta_box, $post, $custom_page;
	$p = $post->post_name;	
	
	if($post->post_type == 'page') {
		$p = basename( get_page_template() );	
	} else if(in_category('news', $post->ID)) {
		$p = 'post-news';
	} else {
		$p = get_post_meta($post->ID, 'inic_post_template', true);
	}
	
	if(in_array($p, $custom_page)) {
		#if(array_key_exists($p, $custom_page)) {
		$key = array_search($p, $custom_page);
		$value = $meta_box[$key];
		return $value;
		#add_meta_box($value['id'], $value['title'], 'plib_format_box', $value['type'], $value['context'], $value['priority']);
	}
	return false;
}
function page_add_custom_box() {
	global $meta_box, $post, $custom_page;
	
	$value = get_meta_option();	
	
	add_meta_box($value['id'], $value['title'], 'plib_format_box', $value['type'], $value['context'], $value['priority']);
	
}
add_action( 'add_meta_boxes', 'page_add_custom_box' );

function my_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', get_bloginfo('template_url') . '/js/upload-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}
function my_admin_styles() {
	wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');
//Formatting
function plib_format_box() {
  global $meta_box, $post, $custom_page;

	$value = get_meta_option();		
	$meta_box_fields = $value['fields'];
	
  // verification
  echo '<input type="hidden" name="plib_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

  echo '<table class="form-table">';
  foreach ($meta_box_fields as $field) {
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);

      echo '<tr>'.
              '<th style="width:20%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
              '<td>';
      switch ($field['type']) {
          case 'text':
              echo '<input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:97%" />'. '<br /><small>'. $field['desc'].'</small>';
              break;
          case 'textarea':
              echo '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. ($meta ? $meta : $field['default']) . '</textarea>'. '<br />'. $field['desc'];			 
			 break;
          case 'wp_editor':
			  wp_editor( ($meta ? $meta : $field['default']), $field['id'], array( 'textarea_name' => $field['id'], 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) );              
			 break;			 
          case 'select':
              echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '">';
			  echo '<option value=""> --- </option>';			  
			  foreach ($field['options'] as $option) {
                  echo '<option value="'.$option['id'].'" '. ( $meta == $option['id'] ? ' selected="selected"' : '' ) . '>'. $option['title'] . '</option>';
              }
              echo '</select><br /><small>'. $field['desc'].'</small>';
              break;
          case 'category':
			  echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '">'; ?>
				<option value="" <?php selected( $meta, '' ); ?>> --- </option>
				<?php 				
				$sa_cats = get_categories();								
				foreach( $sa_cats as $sa_cat ) :
					#echo '<option value="'.$sa_cat->cat_ID.'" '. ( $sa_cat->cat_ID == $meta ? ' selected="selected"' : '' ) . '>'. $sa_cat->cat_name . '</option>';
					?>
					<option value="<?php echo $sa_cat->cat_ID?>" <?php selected( $meta, $sa_cat->cat_ID ); ?>><?php echo $sa_cat->cat_name?></option>
					<?php
				endforeach;              
				echo '</select><br /><small>'. $field['desc'].'</small>';
			  break;			  
          case 'radio':
              foreach ($field['options'] as $option) {
                  echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' checked="checked"' : '' ) . ' />' . $option['name'];
              }
              break;
          case 'checkbox':
              echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ( $meta ? ' checked="checked"' : '' ) . ' /><br /><small>'. $field['desc'].'</small>';
              break;			  
		  case 'imagefile':
			  $image_src = '';
			  if($meta) {
				$image_attributes 	= wp_get_attachment_image_src( $meta );
				$image_src			= $image_attributes[0];
			  }
			  echo '<img id="'. $field['id'] .'_preview" class="image_preview" style="'.($image_src ? '' : 'display:none;').'" src="'. ($image_src ? $image_src : $field['default']) . '"/><br />';
			  echo '<input type="button" value="Select Image" class="upload_image button button-large" id="', $field['id'], '" />';
			  echo '<input type="hidden" name="', $field['id'], '" id="', $field['id'], '_field" value="', $meta , '" />';
			  break;		  
		  case 'button':
			  echo '<input type="button" class="button button-large" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
			  break;
			  
      }
	  ?>
	  <?php
      echo     '<td>'.'</tr>';
  }

  echo '</table>';

}
// Save data from meta box
function plib_save_data($post_id) {
    global $meta_box,  $post, $custom_page;

    //Verify
    if (!wp_verify_nonce($_POST['plib_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    //Check > autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    //Check > permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

	$value = get_meta_option();		
	$meta_box_fields = $value['fields'];
	
    foreach ($meta_box_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

add_action('save_post', 'plib_save_data'); 

function get_post_list(){
	$posts = array();
	$args = array(
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	
	$features = get_posts($args);
	foreach ( $features as $feature ) :
	
		$posts[] = array('id'=>$feature->ID, 'title'=>$feature->post_title );
	
	endforeach;

	$args = array(
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	
	$features = get_pages($args);
	foreach ( $features as $feature ) :
	
		$posts[] = array('id'=>$feature->ID, 'title'=>$feature->post_title );
	
	endforeach;
	return $posts;
}
?>