<div id="password-lost-form" class="minform">
	<?php if ( $attributes['show_title'] ) : ?>
		<h3><?php _e( 'Forgot Your Password?', 'personalize-login' ); ?></h3>
	<?php endif; ?>

	<?php
		global $theme_profiler;
		echo $theme_profiler->get_template_html( 'notification', $attributes );
	?>

	<div class="alert alert-info">
		<small>
		<?php
			_e(
				"Enter your email address and we'll send you a link you can use to pick a new password.",
				'personalize_login'
			);
		?>
		</small>
	</div>

	<form id="lostpasswordform" class="frmvalidate" action="<?php echo wp_lostpassword_url(); ?>" method="post">

		<div class="form-group">
			<label for="user_login" class="sr-only"><?php _e( 'Email', 'personalize-login' ); ?></label>
			<input type="email" name="user_login" placeholder="Email Address" id="user_login" class="form-control" value="" size="20" required/>
		</div>
		<p class="lostpassword-submit">
			<input type="submit" name="submit" class="btn-marginTop btn btn-f24 btn-custom btn-default btn-orange btn-width60"
			       value="<?php _e( 'Reset Password', 'personalize-login' ); ?>"/>
		</p>
	</form>
</div>