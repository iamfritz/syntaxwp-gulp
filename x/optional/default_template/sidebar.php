<?php
/**
 * Right Sidebar displayed on post and blog templates.
 *
 * @package WordPress
 */
?>

<?php if ( is_active_sidebar( 'sidebar-blog' )  ) : ?>
	<aside id="secondary" class="sidebar widget-area" role="complementary">
		<?php dynamic_sidebar("sidebar-blog"); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>