<div class="wrap">
<h1>Paypal Settings</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'paypal-settings' ); ?>
    <?php do_settings_sections( 'paypal-settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Paypal Email</th>
        <td><input type="text" name="paypal_email" value="<?php echo esc_attr( get_option('paypal_email') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Sandbox</th>
        <td><input type="checkbox" name="paypal_sandbox" value="1" <?php echo get_option('paypal_sandbox')  ? 'checked':''; ?>/></td>
        </tr>
    
    </table>
    
    <?php submit_button(); ?>

</form>
</div>