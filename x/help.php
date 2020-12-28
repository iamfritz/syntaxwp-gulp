<?php global $shortcodes_list; ?>
<div class="wrap">
	<h1>Documentation</h1>

	<h4>Shortcodes <small>shorcodes.php</small></h4>
	<table>
	<?php			
	foreach ($shortcodes_list as $key => $value) {            
		echo "
			<tr>
				<td><span style='font-family: monospace;'>[$key]</span></td>
				<td>".$value[1]."</td>
			</tr>";
	}			
	?>
	</table>
	<p>Reference here: <a href="https://developer.wordpress.org/reference/functions/bloginfo/">https://developer.wordpress.org/reference/functions/bloginfo/</a></p>

	<h4>Template Tags  <small>template-tags.php</small></h4>
	<h4>Register Sidebar  <small>register-sidebar.php</small></h4>
	<h4>Register Post Type  <small>register-post-types.php</small></h4>
	<h4>Plugins  <small>plugins.php</small></h4>
	<?php
	#check plugin needed
	if( !function_exists( 'the_field' ) ) { ?>			
		<div class="updated notice" id="message">
			<p>Please install Advance Custom Field Pro</p>
		</div>
	<?php } ?>
	<h4>Load Assets  <small>enqueue-assets.php</small></h4>
	<h4>Custom Widgets  <small>custom-widgets.php</small></h4>
	<h4>Custom Hooks  <small>custom-hooks.php</small></h4>
	<h4>Configuration  <small>config.php</small></h4>
	<h4>Bootstrap  <small>bootstrap.php</small></h4>
	
	<!-- <h4><span style="cursor: pointer;" onclick="">View Sample Code</span></h4>
	
	<div class="">
		<?php 
			#$readme = fopen(dirname(__FILE__).'/readme.txt', 'r') or echo 'unable to open readme';
			#echo $readme;
		?>
	</div> -->
</div>