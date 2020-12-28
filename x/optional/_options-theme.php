<?php
#require_once( get_stylesheet_directory() . '/theme-options.php' ); # put in function.php
/**
 * THEME Options functions
 * REF: http://blog.themeforest.net/wordpress/create-an-options-page-for-your-wordpress-theme/
 * 
 *    
 **/   
  
global $themename, $shortname;

$options = array (
			
	array(	"type" => "open"), # for start of options
	
	/* array(	"name" => "Title",
			"desc" => "Enter a title to display for your welcome message.",
			"id" => $shortname."_welcome_title",
			"std" => "",
			"type" => "text"),
			
	array(	"name" => "Message",
			"desc" => "Text to display as welcome message.",
            "id" => $shortname."_welcome_message",
            "type" => "textarea"),
	
	array(  "name" => "Enable Welcome Message?",
			"desc" => "Check this box if you would like to DISABLE the welcome message.",
            "id" => $shortname."_welcome_disable",
            "type" => "checkbox",
            "std" => "true"),
    array( "name" => "Footer copyright text",
           "desc" => "Enter text used in the right side of the footer. It can be HTML",
           "id" => $shortname."_footer_text",
           "type" => "text",
           "std" => ""), */

	array( "name" => "Post Category Slider",
           "desc" => "Choose post category on slider",
           "id" => $shortname."categoryslider",
           "type" => "categories",
           "std" => ""), 

	array( "name" => "Post Type Slider",
           "desc" => "Choose post type on slider",
           "id" => $shortname."posttypeslider",
           "type" => "posttype",
           "std" => ""), 		   
		   
  array(	"name" => "Facebook Account",
			"desc" => "Facebook Account URL",
            "id" => $shortname."facebook",
            "type" => "text",
			"std" => "http://www.facebook.com/"),

  array(	"name" => "Twitter Account",
			"desc" => "Twitter Account URL",
            "id" => $shortname."twitter",
            "type" => "text",
			"std" => "http://www.twitter.com/"),

  array(	"name" => "LinkenIn Account",
			"desc" => "LinkenIn Account URL",
            "id" => $shortname."linkedIn",
            "type" => "text"),                        
  array(	"name" => "Youtube Account",
			"desc" => "Youtube Account URL",
            "id" => $shortname."youtube",
            "type" => "text",
			"std" => "http://www.youtube.com/"),
  array(	"name" => "Instagram Account",
			"desc" => "Instagram Account URL",
            "id" => $shortname."instagram",
            "type" => "text",
			"std" => "http://www.instragram.com/"),			
  array(	"name" => "Email Account",
			"desc" => "Email Address",
            "id" => $shortname."email",
            "type" => "text"),  
            
	array(  "name" => "Display Social Networking Button?",
			"desc" => "Check this box if you would like to DISPLAY the Social Networking Icons (Facebook, Twitter, linkedIn).",
            "id" => $shortname."social_disable",
            "type" => "checkbox",
            "std" => true),

    array( "name" => "Google Analytics Code",
           "desc" => "Paste your Google Analytics or other tracking code in this box.",
           "id" => $shortname."ga_code",
           "type" => "textarea",
           "std" => ""),                    

	array(  "name" => "Add Google Analytics?",
			"desc" => "Add Google Analytics in site. Disable this if you are in testing.",
            "id" => $shortname."ga_disable",
            "type" => "checkbox",
            "std" => "false"),  
    
	array( "name" => "Post Navigation",
           "desc" => "Listing Navigation",
           "id" => $shortname."navigation",
           "type" => "select",
		   "options" => array('navigation','pagination'),
           "std" => ""), 	
		   
	array(	"type" => "close") # for end of options
	
);
#add menu
add_action('admin_menu', 'myCustom_add_admin');
function myCustom_add_admin() {
  
    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=theme-options.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=theme-options.php&reset=true");
            die;

        }
    }  
		
  	$pagehook = add_theme_page($themename." Options", "Theme Options", 'edit_theme_options', basename(__FILE__), 'myCustom_admin');
	#add_action('admin_head-' . $pagehook, 'othertheme_admin_page_head');
	add_action('admin_print_styles-' . $pagehook, 'myCustom_add_style');  
	#add_action('admin_print_scripts-' . $pagehook, 'othertheme_admin_print_scripts');
} #myCustom_add_admin 

function myCustom_add_style() {
	$file_dir = get_bloginfo('template_directory');
	wp_enqueue_style("functions", $file_dir."/css/style-admin.css", false, "1.0", "all");
} #myCustom_add_style
add_action( 'admin_head', 'myCustom_add_style' );

function myCustom_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
?>

<div class="wrap">
	<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>
<form method="post">
<?php 

	foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		  case "open":
		?>
    <table width="100%" border="0" cellspacing="5" class="myCustoms">                
		<?php 
      break;		
		  case "close": ?>
		</table>                
		<?php 
      break;
		  case 'text':
		?>        
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
          <input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
		<?php 
      break;		
		  case 'textarea':
		?>        
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
          <textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea>
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
		<?php 
		  break;		
		  case 'select':
		?>
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
          <select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		  <?php foreach ($value['options'] as $option) { ?>
			<option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
		  <?php } ?>
		  </select>
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
		<?php
      break;            
		  case "checkbox":      
		?>
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
          <?php if(get_settings($value['id'])){ $checked = "checked=\"checked\""; } else{ $checked = ""; } ?>
          <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
    <?php 
    break;
		  case 'categories':
		?>
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
		<?php $args = array(
						'show_option_all'    => ' none ',
						'selected'           => get_settings( $value['id'] ),
						'name'				 => $value['id']
					);
				wp_dropdown_categories( $args ); 
			?>
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
		<?php
	break;
		case 'posttype':
		?>
      <tr>
        <td width="20%" valign="top"><h4><?php echo $value['name']; ?></h4></td>
        <td width="80%">
          <select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
			<option value="">none</option>
		  <?php 
				$post_types = get_post_types( array('public'   => true), 'objects'); 

				foreach ( $post_types as $post_type ) {
				   $option = $post_type->name;
		  ?>
			<option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
		  <?php } ?>
		  </select>
          <small>get_option(<?php echo $value['id']; ?>)</small><span class="description"><?php echo $value['desc']; ?></span>
        </td>
      </tr>
		<?php
      break;
      break;  	
    default;
    break;
  } # Switch 
} #foreach
?>
<!--</table>-->
<p class="submit">
<input name="save" type="submit" value="Save changes" class="button button-primary button-large" />    
<input type="hidden" name="action" value="save" /></p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" class="button button-larg" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<div class="clear"></div>
</div>
<?php
} #myCustom_admin()
  
function get_myCustomOptions(){
  global $options;
  foreach ($options as $value) {
      if (get_option( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_option( $value['id'] ); }            
  }      
}
/**
 * sample
 * 
 *
  $welcome = c_welcome(false);
  if ($welcome) {
     _e($welcome);
  } else {}    
 ***/   
 
function c_welcome($ret=true) {  
  $contents = '';
  if (get_option($shortname.'welcome_disable')) {       
    $contents .= '<div class="welcome_content">';
    $contents .= get_option($shortname.'welcome_title') ? '<div class="bl"><h2>'.get_option($shortname.'welcome_title').'</h2></div>' : '';
    $contents .= '<div class="text_content wh">
				 <img width="144px" alt="" src="'.get_bloginfo('template_url').'/images/certified.jpg" style="float:right; padding-right:5px; margin-left:10px;">
				 '.get_option($shortname.'welcome_message').'</div>';
    $contents .= '</div>';
    if($ret): echo $contents;
    else:     return $contents; 
    endif;
  }
} #c_welcome  

function c_social_icons() {
  global $shortname;
  $icons = '';
  if (get_option($shortname.'social_disable')) {    
    $icons .= (get_option($shortname.'facebook')) ? '<li class="t_facebook"><a class="social" href="'.get_option($shortname.'facebook').'" target="_blank">f</a></li>' : '';
    $icons .= (get_option($shortname.'twitter')) ? '<li class="t_twitter"><a class="social" href="'.get_option($shortname.'twitter').'" target="_blank">t</a></li>' : '';
    $icons .= (get_option($shortname.'linkedIn')) ? '<li class="t_linkedIn"><a class="social" href="'.get_option($shortname.'linkedIn').'" target="_blank">i</a></li>' : '';
	$icons .= (get_option($shortname.'youtube')) ? '<li class="t_youtube"><a class="social" href="'.get_option($shortname.'youtube').'" target="_blank">x</a></li>' : '';
	$icons .= (get_option($shortname.'instagram')) ? '<li class="t_youtube"><a class="social" href="'.get_option($shortname.'instagram').'" target="_blank">n</a></li>' : '';
    $icons .= (get_option($shortname.'email')) ? '<li class="t_email"><a class="social" href="mailto:'.get_option($shortname.'email').'&subject=Enquery" target="_blank">y</a></li>' : '';

    if (!empty($icons)) {
      $file_dir=get_bloginfo('template_directory');
	    wp_enqueue_style("functions", $file_dir."/css/theme-options.css", true, "1.0", "all");
      echo '<div class="socialmedia">
	  			<ul class="t_social_icons">'.$icons.'</ul>
			</div> 
			';
    }        
  
  }
} #c_social_icons

function c_footer() {
  echo get_option($shortname.'footer_text');
} #c_footer

function c_ga_code(){
  if (get_option($shortname.'ga_disable')) {    
	?>
  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo get_option($shortname.'ga_code');?>']);
    _gaq.push(['_trackPageview']);
    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>      
	<?php  
  }  
} #ga_code

?>