<?php
#http://wordpress.stackexchange.com/questions/193142/upload-multiple-image-with-media-handle-upload-with-multiple-file-field
class WP_Site_Option{
    function __construct(){
        add_action('admin_init', array(&$this,'register_settings') );
        add_action('admin_menu', array(&$this,'create_menu'));  
        add_action('admin_print_scripts', array(&$this,'add_scripts') );
        add_action('admin_print_styles', array(&$this,'add_styles') );
    }

    public function create_menu(){
        add_menu_page('Site Options', 'Site Options', 'administrator', 'site-options', array(&$this,'settings'),$src = '',62);
    }

    public function register_settings(){
        register_setting( 'starter_setting_group', 'starter' );
    }

    public function add_styles(){
        wp_enqueue_style('thickbox');
    }

    public function add_scripts(){
        wp_enqueue_script('thickbox');
        wp_enqueue_script('meida-upload');      
    }

    public function settings(){
        if(!current_user_can('manage_options')){
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

?>
        <div class="wrap">
            <h2>Site Options</h2>
            <form method="post" action="options.php"> 
                <?php @settings_fields('starter_setting_group'); ?>
                <?php @do_settings_fields('starter_setting_group'); ?>
                <?php $content = get_option('starter'); ?>
                <table class="form-table">  
                    <script>
                        var count = 1;
                    </script>

                    <!-- loop -->

                        <?php foreach($content['logo'] as $logo): ?>
                        <tr>
                        <th></th>
                        <td>
                            <input id="upload_image" type="text" size="36" name="starter[logo][]" value="<?php echo $logo; ?>"  class="regular-text upload-image"/>
                            <input id="upload_image_button" type="button" value="Upload Image" class="upload_image_button"/>
                            <br />Enter an URL or upload an image for the banner.
                            <br/>
                            <?php 
                                if($logo): 
                                    $url = $logo;
                            ?>
                                <img src="<?php echo $url; ?>" height="100" style="float:left"/>
                            <?php endif; ?>
                            <div id="temp"></div>
                        </td>
                        </tr>
                        <?php endforeach; ?>

                    <!-- loop end -->

                    <div class="more-image"></div>
                    <tr>
                        <td>
                            <input type="button" id="add-new-image" class="add-new-image" value="Add More"/>
                        </td>
                    </tr>
                </table>

                <?php @submit_button(); ?>
                <script language="JavaScript">
                    var formfield = '';
                    jQuery(document).ready(function() {
                        jQuery('.add-new-image').on('click',function(){
                            var uploaded = jQuery('.upload-image');
                            var count = uploaded.length + 1;
                            var to_rep = '<tr class="repetable-tr"><th scope="row"><label>Upload Logo</label></th><td><input id="upload_image_'+count+'" type="text" size="36" name="starter[logo][]" value=""  class="regular-text upload-image"/><input id="upload_image_button_'+count+'" type="button" value="Upload Image" class="upload_image_button"/><br />Enter an URL or upload an image for the banner.<div id="temp"></div></td></tr>';
                            jQuery('.more-image').append(to_rep);
                        });

                        jQuery('.upload_image_button').live('click',function() {
                            formfield = jQuery(this).prev().attr('id');

                            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
                            jQuery('#TB_iframeContent').css('width', '800px');
                            jQuery('#TB_window').css('width', '800px');
                            return false;
                        });

                        window.send_to_editor = function(html) {
                            imgurl = jQuery('img', html).attr('src');
                            jQuery('#'+formfield).val(imgurl);
                            //jQuery('#temp').html('<img src="'+imgurl+'" width="200">');
                            tb_remove();
                        }
                    });
                </script>
            </form>
        </div>
<?php
    }
}
$wp_site_option = new WP_Site_Option();
    ?>