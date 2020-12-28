<?php
/** Enqueues scripts and styles.
* 
*/
class loadAssets {

	var $css_array = [];

	function __construct($css_array = array(), $js_array = array()) {
		
		$this->css_array 	= $css_array;
		$this->js_array 	= $js_array;
		add_action('wp_head', array($this, 'add_global_script') ) ;
		add_action('wp_enqueue_scripts',  array($this, 'scripts') );
		add_action('admin_enqueue_scripts', array($this, 'admin_style'));
	}


    public static function init() {
        $class = __CLASS__;
        new $class;      
	}

	/**
	 * load themes js and css
	 *
	 * @return void
	 * @author 
	 **/
	
	function scripts() {		

		$css_array = array(
					't-style' 			=> get_stylesheet_uri(),
					's-app' 			=> ASSETS_CSS. 'app.css'
					);

		$js_array = array(
					's-app' 		    => ASSETS_JS. 'app.js'
					);

		$this->js_array 	= array_merge($this->js_array, $js_array);		
		$this->css_array 	= array_merge($this->css_array, $css_array);
		
		foreach($this->css_array as $name => $url) {
			wp_enqueue_style( $name, $url );
		}

		wp_enqueue_script("jquery");
		
		foreach($this->js_array as $name => $url) {
			wp_enqueue_script( $name, $url, array(), '1.0', true );
		}		

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	} //sac_scripts

	/**
	 * add js in header
	 *
	 * @return void
	 * @author 
	 **/
	function add_global_script() {
		?>
		<script type="text/javascript">
			// Code that uses other library's $ can follow here.		
			var _wp = [] 
				_wp['baseurl'] = '<?php bloginfo('url'); ?>';
				_wp['ajaxurl'] = '<?php echo admin_url('admin-ajax.php'); ?>';			
		</script>	
		<?php
	} //add_global_script

	// Update CSS within in Admin
	function admin_style() {
	  wp_enqueue_style('admin-styles', ASSETS_CSS.'admin.css');
	}	

}//enqueues

/*
 add additional js and css set in config.php
 */
$css_array 	= unserialize( css_array );
$js_array 	= unserialize( js_array );

$loadAssets = new loadAssets($css_array, $js_array);

#wp_enqueue_script( $theme_handle_prefix . '-scripts', get_template_directory_uri() . '/assets/js/' . $theme_handle_prefix . '.js', array( 'jquery' ), '1.0.0', true );
#wp_enqueue_style( $theme_handle_prefix . '-styles', get_template_directory_uri() . '/assets/css/' . $theme_handle_prefix .'.css', array(), '1.0.0', 'all' );