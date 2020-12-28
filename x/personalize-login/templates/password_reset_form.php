<div id="password-reset-form" class="minform">
	<?php if ( $attributes['show_title'] ) : ?>
		<h3><?php _e( 'Pick a New Password', 'personalize-login' ); ?></h3>
	<?php endif; ?>
	<?php
		global $theme_profiler;
		echo $theme_profiler->get_template_html( 'notification', $attributes );
	?>
	<form name="resetpassform" id="resetpassform" class="frmvalidate" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
		<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

		<div class="form-group">
			<label for="pass1" class="sr-only"><?php _e( 'Repeat new password', 'personalize-login' ) ?></label>
			<input type="password" name="pass1" id="pass1" class="form-control" size="20" value="" placeholder="New password*" autocomplete="off" required/>
		</div>

		<div class="form-group">
			<label for="pass2" class="sr-only"><?php _e( 'Repeat new password', 'personalize-login' ) ?></label>
			<input type="password" name="pass2" id="pass2" class="form-control" size="20" value="" placeholder="Repeat new password*" autocomplete="off" data-rule-equalTo="#pass1" required/>
		</div>

		<div class="alert alert-info">
			<small class="description"><?php echo wp_get_password_hint(); ?></small>
		</div>

		<p class="resetpass-submit">
			<input type="submit" name="submit" id="resetpass-button"
			       class="btn-marginTop btn btn-f24 btn-custom btn-default btn-orange btn-width60" value="<?php _e( 'Reset Password', 'personalize-login' ); ?>" />
		</p>
	</form>	
</div>