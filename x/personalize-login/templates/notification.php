    <!-- Show errors if there are any -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : $newline = '' ?>
        <div class="alert alert-danger fade in ">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
                <?php echo "$newline<strong>ERROR</strong>: $error"; $newline = '<br>'?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes['logged_out'] ) : ?>
        <div class="alert alert-info fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php _e( '<strong>INFO</strong>: You have signed out. Would you like to sign in again?', 'personalize-login' ); ?>
        </div>
    <?php endif; ?>

    <?php if ( $attributes['registered'] ) : ?>
        <div class="alert alert-info fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php
                printf(
                    __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'personalize-login' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </div>
    <?php endif; ?>

    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <div class="alert alert-info fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php _e( 'Check your email for a link to reset your password.', 'personalize-login' ); ?>
        </div>
    <?php endif; ?>

    <?php if ( $attributes['password_updated'] ) : ?>
        <div class="alert alert-info fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php _e( 'Your password has been changed. You can sign in now.', 'personalize-login' ); ?>
        </div>
    <?php endif; ?>    