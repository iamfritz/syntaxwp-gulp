<?php
class sac_help {

	function __construct()
	{

		add_action('admin_menu', array($this, 'menu'));
    }

	function menu() {
		add_theme_page('Help', 'Theme Help', 'edit_theme_options', 'sac-helper', array($this, 'help_page') );
	}

	function help_page(){
		include get_template_directory() . '/libraries/help.php';
	}
	
} //sac_help

new sac_help();
?>