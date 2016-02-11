<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'tokopress' ); ?></p>
	<?php return; ?>
<?php endif; ?>
	
<?php if ( comments_open() &&  post_type_supports( get_post_type(), 'comments' ) ) : ?>



	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
    <section id="comments" class="clearfix">
		<div class="col-md-3">
			<h2 class="comments-title"><span>
				<?php _e( 'Comments', 'tokopress' ); ?>
			</span></h2>
		</div><!-- column 3 -->

		<div class="col-md-9">
			<div class="commentslist-wrap">

				<ol class="commentlist">
					<?php wp_list_comments( 'callback=tokopress_comments' ); ?>
				</ol>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comment-nav-below">
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tokopress' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tokopress' ) ); ?></div>
				</nav>
				<?php endif; // check for comment navigation ?>
			</div>
		</div><!-- Column 9 -->

	</section><!-- #comments -->
	<?php endif; ?>


	<div id="comments-block" class="clearfix">
	    
		<div class="col-md-3">
			<h2><?php _e( 'Leave a comment', 'tokopress' ) ?></h2>
		</div>
		<div class="col-md-9">
			
		<?php
		    $comment_args = array(
		        'title_reply'           => '',
		        'fields'                => apply_filters( 
		                                    'comment_form_default_fields',

		                                    array(
		                                        'block-open'    => '',
		                                        'author'        => '<p><input id="input_name" class="input-text" name="author" type="text" placeholder="' . __( 'Name *', 'tokopress' ) . '" /></p>',   
		                                        'email'         => '<p><input id="input_email" class="input-text" name="email" type="email" placeholder="' . __( 'Email *', 'tokopress' ) . '" /></p>',
		                                        'url'           => '<p><input id="input_url" class="input-text" name="url" type="text" placeholder="' . __( 'Website', 'tokopress' ) . '" /></p>',
		                                        'block-close'   => ''
		                                    )
		                                ),
		        'comment_field'         => '<p><textarea id="comment_message" class="input-text" rows="10" name="comment" placeholder="' . __( 'post your comment here! *', 'tokopress' ) . '" aria-required="true"></textarea></p>',
		        'comment_notes_after'   => '',
		        'label_submit'          => __( 'Post comment', 'tokopress' )
		    );

		    ?>
			
			<?php comment_form( $comment_args ); ?>
		</div>
	</div>


<?php endif; ?>