<?php
/** Enqueues scripts and styles.
* 
*/
class load_assets {

	var $css_array = 'test';

	function __construct($css_array = array(), $js_array = array()) {
		
		$this->css_array 	= $css_array;
		$this->js_array 	= $js_array;
		add_action('wp_head', array($this, 'add_global_script') ) ;
		add_action('wp_enqueue_scripts',  array($this, 'sac_scripts') );
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
	
	function sac_scripts() {		

		$css_array = array(
					'sac-style' 			=> get_stylesheet_uri(),
					'sac-front' 		=> ASSETS_CSS. 'style.css',
					'sac-bootstrap' 			=> ASSETS_CSS. 'bootstrap.min.css',
					'sac-libraries' 		=> ASSETS_CSS. 'libraries.css',
					'sac-font-awesome' 		=> ASSETS_CSS. 'font-awesome.min.css',
					'sac-fatNav' 			=> ASSETS_CSS. 'jquery.fatNav.css',
					'sac-cf7' 				=> ASSETS_CSS. 'cf7.css',
					);
		
		$js_array = array(
					/*'sac-jqueryui' 	=> ASSETS_JS. 'jquery-ui.min.js',
					'sac-jqueryui-touch' 	=> ASSETS_JS. 'jquery.ui.touch-punch.min.js',
					'sac-fancybox' 			=> ASSETS_JS. 'jquery.fancybox.min.js',
					'sac-fancybox-button' 	=> ASSETS_JS. 'jquery.fancybox-buttons.js',*/
					'sac-modernizr' 		=> ASSETS_JS. 'modernizr.2.8.3.min.js',
					/*'sac-carousel' 			=> ASSETS_JS. 'owl.carousel.min.js',*/			
					'sac-bootstrap' 		=> ASSETS_JS. 'bootstrap.min.js',
					'sac-fatNav' 		=> ASSETS_JS. 'jquery.fatNav.js',
					'sac-global-scripts' 	=> ASSETS_JS. 'global.js',
					'sac-custom-scripts' 	=> ASSETS_JS. 'script.js',
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
			/*jQuery(document).ready(function($) {
				jQuery.noConflict();
			});	*/			
		</script>	
		<?php
	} //add_global_script

	// Update CSS within in Admin
	function admin_style() {
	  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assets/css/admin.css');
	}	

}//sac_enqueues

/*
 add additional js and css set in config.php
 */
$css_array 	= unserialize( css_array );
$js_array 	= unserialize( js_array );

$load_assets = new load_assets($css_array, $js_array);