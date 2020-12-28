GMG
- themes that auto install to the themes if the ACF not exist
- acfexport is the sample fields created


/**************** sac-ctech/libraries/config.php ****************/
define( 'URI_ASSETS', get_bloginfo('template_directory').'/assets/' );
define( 'URI_TEMPLATES', get_bloginfo('template_directory').'/templates/' );

/**************** enable or disable search ****************/
define( 'ENABLE_SEARCH', false );

/**************** hide acf admin menu ****************/
define( 'HIDE_ACF', false );

/**************** sac-ctech/libraries/config.php ****************/

/**************** Banner Slider ****************/
<?php
// check if the repeater field has rows of data
if( have_rows('banner', 'option') ):
    // loop through the rows of data
    $active   = 'active';
  $indicators = '';
  $x          = 0;
    while ( have_rows('banner', 'option') ) : the_row();
      $url = get_sub_field('button_url') ? get_sub_field('button_url') : get_sub_field('page_url');
      $indicators .= '<li data-target="#bannerslider" data-slide-to="'.$x.'" class="'.$active.'"></li>';
    ?>
        <div class="item <?php echo $active;?>" style="background-image: url(<?php the_sub_field('image');?>);">
          <div class="container container-fluid padding110">  
          <article>
            <h3><a href="<?php echo $url;?>"><?php the_sub_field('title');?></a></h3>
            <?php the_sub_field('content');?>
          </article>
          </div>
        </div>    
    <?php
    $active = ''; $x++;
              
    endwhile;
endif;
?>   
<?php
    if (locate_template("templates/section-slider.php") != '') {      
        get_template_part( "templates/section-slider" );
    }
?>

/**************** Banner Slider ****************/


Theme General Settings

/**************** Logos and Fav icon ****************/
<?php 
	$favicon 	= get_field('fav_icon', 'option') ? get_field('fav_icon', 'option') : URI_ASSETS.'images/favicon.ico';
	$logo 		= get_field('logo', 'option') ? get_field('logo', 'option') : URI_ASSETS.'images/logo.png';
?>
/**************** Logos and Fav icon ****************/

/**************** Social icon ****************/
<?php
// check if the repeater field has rows of data
if( have_rows('social_account', 'option') ):
    // loop through the rows of data
    while ( have_rows('social_account', 'option') ) : the_row();
       // display a sub field value
        the_sub_field('sub_field_name');
        echo "<a href='".get_sub_field('url')."' target='_blank'><i class='social fa fa-".get_sub_field('account')." fa-lg fa-border'></i></a>";                  
    endwhile;
endif;
?> 
/**************** Social icon ****************/

/**************** Contacts Details ****************/
<?php the_field( 'contact_email', 'option' ); ?>
<?php the_field( 'contact_number', 'option' ); ?>
/**************** Contacts Details ****************/


/**************** Footer Information ****************/
{Y}         = Year
{SITENAME}  = sitename
<?php echo str_replace(array('{Y}', '{SITENAME}'), array(date('Y'), get_bloginfo('name')), get_field('footer_info', 'option')); ?>
<?php the_field( 'footer_title', 'option' ); ?>
/**************** Footer Information ****************/

/**************** Google Analytics ****************/
#on_sent_ok: "ga('send', 'event', 'Contact Form', 'submit');" CF7
<?php the_field( 'analytics_script', 'option' ); ?>
/**************** Google Analytics ****************/

/**************** Developer ****************/
add ?debug=1 to url to trigger error display
/**************** Developer ****************/

/**************** Contact Us Page ****************/
#display map
<?php
    if (locate_template("templates/section-acf-map.php") != '') {      
        get_template_part( "templates/section-acf-map" );
    }
?>
/**************** Contact Us Page ****************/

/**************** Widgets ****************/
refer to sac-ctech/libraries/register-sidebar.php to check all register sidebar
<?php
if (function_exists('dynamic_sidebar')) {
	dynamic_sidebar("footer-one");
} ?>
/**************** Widgets ****************/


/**************** Admin login logo ****************/
replace assets/images/logo-admin.png
you can adjust the CSS at  sac-ctech/libraries/custom-hook.php under custom_login_logo() function
/**************** Admin login logo ****************/