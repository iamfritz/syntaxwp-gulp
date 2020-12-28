<div id="register-form" class="widecolumn">
	<?php if ( $attributes['show_title'] ) : ?>
		<h3><?php _e( 'Register', 'personalize-login' ); ?></h3>
	<?php endif; ?>

	<?php
		global $theme_profiler;
		echo $theme_profiler->get_template_html( 'notification', $attributes );
	?>

	<form id="signupform" class="frmvalidate" action="<?php echo wp_registration_url(); ?>" method="post">

		<div class="form-group">
			<label for="first_name" class="sr-only"><?php _e( 'First name', 'personalize-login' ); ?></label>
			<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First name*" required>
		</div>

		<div class="form-group">
			<label for="last_name" class="sr-only"><?php _e( 'First name', 'personalize-login' ); ?></label>
			<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last name*" required>
		</div>

		<div class="form-group">
			<label for="email" class="sr-only"><?php _e( 'Email', 'personalize-login' ); ?></label>
			<input type="email" name="email" id="email" class="form-control" placeholder="Email Address*" required>
		</div>
		<div class="alert alert-info">
		<small>
			<?php _e( 'Note: Your password will be generated automatically and emailed to the address you specify above.', 'personalize-login' ); ?>
		</small>
		</div>

		<!-- <div class="form-group">
			<label for="pass1" class="sr-only">Password</label>
			<input autocomplete="off" name="pass1" id="pass1" class="form-control" size="20" value="" type="password" placeholder="Password*" />
		</div>
		<div class="form-group">
			<label for="pass2" class="sr-only">Confirm Password</label>
			<input autocomplete="off" name="pass2" id="pass2" class="form-control" size="20" value="" type="password" placeholder="Confirm Password*" />
		</div> -->

		<?php if ( $attributes['recaptcha_site_key'] ) : ?>
			<div class="recaptcha-container">
				<div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
			</div>
		<?php endif; ?>

		<p class="signup-submit">
			<input type="submit" name="submit" class="btn-marginTop btn btn-f24 btn-custom btn-default btn-orange btn-width60"
			       value="<?php _e( 'Register', 'personalize-login' ); ?>"/>
		</p>
	</form>
	
	<p align="center">
		<a class="forgot-password" href="<?php echo $theme_profiler->login_url; ?>">
			<?php _e( 'Login', 'personalize-login' ); ?>
		</a>&nbsp;|&nbsp;
		<a class="forgot-password" href="<?php echo $theme_profiler->lost_pass_url;; ?>">
			<?php _e( 'Forgot your password?', 'personalize-login' ); ?>
		</a>
	</p>	
</div>