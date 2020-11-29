<?php
/**
 * Header template
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php wp_title( '&ndash;', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class() ?>>
	
<!-- Site header markup goes here -->
<div class="wrapper">
      <!-- Navbar Section -->
      <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <div class="container container">
          <a class="navbar-brand" href="#">BSLaravelMix</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-right">
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#features">Features</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#services">Services</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#reviews">Reviews</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#pricing">Pricing</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#buy">Signup</a></li>
            </ul>
          </div>
        </div>
      </nav><!-- Navbar End -->
	  
