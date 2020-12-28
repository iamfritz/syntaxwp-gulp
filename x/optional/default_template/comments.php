<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<div class="header">
		<?php
			printf( _n( '<span class="green">One comment</span> on &ldquo;%2$s&rdquo;', '<span class="green">%1$s comments</span> on &ldquo;%2$s&rdquo;', get_comments_number(), 'fzwebworks' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
		?>
	</div>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'fzwebworks' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'fzwebworks' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fzwebworks' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

	<div id="commentlist">
		<?php
			wp_list_comments( array(
				'style'      => 'div',
				'short_ping' => true,
				'avatar_size'=> 76,
				'callback' 	 => 'sac_comments'
			) );
		?>
	</div><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'fzwebworks' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'fzwebworks' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fzwebworks' ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'fzwebworks' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>
	<div class="lj-clear"></div>
	<?php #comment_form(); ?>

<?php 
	$comment_args = array( 	
							'id_submit'	=> 'btn',
							'title_reply'=>'<div class="header padT20 padB20">Leave a Reply</div>',

							'fields' => apply_filters( 'comment_form_default_fields', 
								array(

									'author' => '<p><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"  placeholder="Name (required)"/></p>',   

    								'email'  => '<p><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" placeholder="Email (required)" /></p>',

    								'url'    => '' )
								),

    						'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Message"></textarea><i>Minimum 50 character.</i><div class="lj-clear"></div>',

    						'comment_notes_after' => '',

						 	'comment_notes_before' => ''    					

	);

	comment_form(); ?>

</div><!-- #comments -->
