<?php
/*
 * Template Name: Demo Page
 * 
 */

get_header();

?>
<div class="Demo">
    <div class="Demo__inner">

        <h1 class="Demo__title">WP Laravel Mix Theme Boilerplate</h1>

        <p>This is a just a very minimalistic theme boiler that gets you started with Laravel Mix for building your
            project assets.</p>

        <h2>Why use Laravel Mix?</h2>

        <p>Simply put, it is a very straight-forward and easy to use wrapper for webpack that removes the need for
            a lot of the configuration that we would normally have to go through when using alternatives.</p>

        <p>For a sense of how simple it is to set up, check out the webpack.mix.js file in the them root.
            <a href="https://laravel.com/docs/5.8/mix" target="_blank">You can learn more about Laravel Mix here.</a>
        </p>

		<?php
		// You may use 'views' to render your UI components. Views are designed for use anywhere as they, ideally, have
		// no context-specific functionality – the simply render data handed to them as follows:
		\WPLMixTheme\View::render( 'DemoView', [
			'title' => 'Who am I?',
			'text' => 'My name is Fritz and I build themes and plugins for WordPress. Contact me <a href="mailto:link4anything@gmail.com" target="_blank">link4anything@gmail.com</a>',
		] ); ?>

    </div>
</div>
<?php

get_footer();