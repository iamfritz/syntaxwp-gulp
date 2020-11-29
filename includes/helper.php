<?php
/**
 * Debugging tools
 *
 */

/**
 * Pretty printing debugging tool
 */
function pr( $var ) {
	print '<pre>';
	print_r( $var );
	print '</pre>';
}
