<!DOCTYPE html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	
	<?php 
		$favicon 	= get_field('fav_icon', 'option') ? get_field('fav_icon', 'option') : URI_ASSETS.'images/favicon.ico';
		$logo 		= get_field('logo', 'option') ? get_field('logo', 'option') : URI_ASSETS.'images/logo.png';
	?>
	<link rel="shortcut icon" href="<?php echo $favicon;?>" type="image/x-icon">
	<link rel="icon" href="<?php echo $favicon;?>" type="image/x-icon">


	<meta name="description" content="<?php if ( is_single() ) {
	    single_post_title('', true); 
	} else {
	    bloginfo('name'); echo " - "; bloginfo('description');
	}
	?>" />
	<meta name="keywords" content="">
	<meta name="author" content="<?php bloginfo('name'); ?>">
	<title><?php wp_title(); ?></title>
	
	<?php wp_head(); ?>

		
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  		
</head>

<body <?php body_class(); ?>>

	<?php #echo get_page_template(); ?>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
          	</button>
			<a class="navbar-brand" href="<?php bloginfo('url'); ?>">
				<img class="logo" src="<?php echo $logo; ?>"  alt="<?php bloginfo('name'); ?>" />
			</a>          
        </div>
        <div id="navbar" class="collapse navbar-collapse">
	        <?php 
				$email_menu = (get_field('contact_email', 'option') ? '<li><a href="mailto:'.get_field('contact_email', 'option').'" class="navEmail">'.get_field('contact_email', 'option').'</a></li>': '');
				wp_nav_menu(array(
				'container_class' 	=> 'menu',
				'theme_location' 	=> 'primary',
				'items_wrap' 		=> '<ul id="%1$s" class="%2$s nav navbar-nav menu">%3$s'.$email_menu.'</ul>',
				'fallback_cb'    	=> 'bootpress_link_to_menu_editor',
				'depth' 			=> 3,
				'walker' 			=> new wp_bootstrap_navwalker,
				)); 
			?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

	 