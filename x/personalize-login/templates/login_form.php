<?php if ( false ) : ?>
<div class="login-form-container minform">
	<?php if ( $attributes['show_title'] ) : ?>
		<h2><?php _e( 'Sign In', 'personalize-login' ); ?></h2>
	<?php endif; ?>
	<?php
		global $theme_profiler;
		echo $theme_profiler->get_template_html( 'notification', $attributes );
	?>

	<?php
		wp_login_form(
			array(
				'label_username' => __( 'Email', 'personalize-login' ),
				'label_log_in' => __( 'Sign In', 'personalize-login' ),
				'redirect' => $attributes['redirect'],
			)
		);
	?>

	<a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
		<?php _e( 'Forgot your password?', 'personalize-login' ); ?>
	</a>

</div>
<?php else : ?>
	<div class="login-form-container minform">
	<?php if ( $attributes['show_title'] ) : ?>
		<h2><?php _e( 'Sign In', 'personalize-login' ); ?></h2>
	<?php endif; ?>
	<?php
		global $theme_profiler;
		echo $theme_profiler->get_template_html( 'notification', $attributes );
	?>	
	    <form name="loginform" class="frmvalidate" id="loginform" method="post" action="<?php echo wp_login_url(); ?>">
	        <input type="hidden" name="redirect_to" value="<?php echo $attributes['redirect'];; ?>" />
	        <div class="form-group">
	            <label for="user_login" class="sr-only"><?php _e( 'Email', 'personalize-login' ); ?></label>
	            <input class="form-control" type="email" name="log" placeholder="Email Address" id="user_login" class="input" value="" size="20" required/>
	        </div>        
	        <div class="form-group">
	        	<label for="user_pass" class="sr-only"><?php _e( 'Password', 'personalize-login' ); ?></label>
	            <input  class="form-control" type="password" placeholder="Password" name="pwd" id="user_pass" class="input" value="" size="20" autocomplete="off" required/>
	        </div       
	        <p class="login-submit">
	            <input type="submit" value="<?php _e( 'Log In', 'personalize-login' ); ?>" class="btn-marginTop btn btn-f24 btn-custom btn-default btn-orange btn-width60">
	        </p>
	    </form>		
	<p align="center">
	<a class="forgot-password" href="<?php echo $theme_profiler->register_url; ?>">
		<?php _e( 'Register', 'personalize-login' ); ?>
	</a>&nbsp;|&nbsp;
	<a class="forgot-password" href="<?php echo $theme_profiler->lost_pass_url;; ?>">
		<?php _e( 'Forgot your password?', 'personalize-login' ); ?>
	</a>
	</p>		
	</div>
<?php endif; ?>
