<?php

/**
 * Charset 
 */
add_action( 'wp_head', 'tokopress_wphead_charset', 0);
function tokopress_wphead_charset() {
?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php 
}

/**
 * Fallback for Title Tag 
 */
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	add_action( 'wp_head', 'tokopress_wphead_title', 0);
	function tokopress_wphead_title() {
?>
<title><?php wp_title(); ?></title>
<?php 
	}
}
else {
	remove_action( 'wp_head', '_wp_render_title_tag', 1);
	add_action( 'wp_head', '_wp_render_title_tag', 0);
}

/**
 * Meta Responsive
 */
add_action( 'wp_head', 'tokopress_wphead_responsive', 0);
function tokopress_wphead_responsive() {
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
}

/**
 * Favicon
 */
add_action( 'wp_head', 'tokopress_wphead_favicon', 0);
function tokopress_wphead_favicon() {
	if ( function_exists( 'get_site_icon_url' ) && get_site_icon_url() )
		return;

	$icon = of_get_option( 'tokopress_favicon' ) ? of_get_option( 'tokopress_favicon' ) : THEME_URI.'/img/favicon.png';
	?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url($icon); ?>" />
	<?php 
}

add_action( 'wp_head', 'tokopress_style_header_image' );
function tokopress_style_header_image() {
	if ( $img = get_header_image() ) {
		echo '<style>.page-title { background-image: url('.esc_url( $img ).'); }</style>';
	}
}

/**
 * Add sticky header status class to <body> class.
 */
add_filter( 'body_class', 'tokopress_header_sticky_class' );
function tokopress_header_sticky_class( $classes ) {
	if ( of_get_option( 'tokopress_sticky_header' ) )
		$classes[] = 'sticky-header-yes';
	else
		$classes[] = 'sticky-header-no';
	return $classes;
}

/**
 * Wrapper main content
 */
add_action( 'tokopress_before_content', 'tokopress_wrapper_content_start', 5 );
function tokopress_wrapper_content_start() {
	echo '<div class="wrapper-content clearfix">';
}
add_action( 'tokopress_after_content', 'tokopress_wrapper_content_end', 45 );
function tokopress_wrapper_content_end() {
	echo '</div>';
}

/**
 * Comment lists
 */
function tokopress_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php echo get_comment_ID(); ?>" <?php echo comment_class(); ?> itemprop="reviews" itemscope="" itemtype="http://schema.org/Review">
		<div class="comment-meta">
			<div class="author-avatar">
				<?php echo get_avatar( $comment, $size='40' ); ?>
			</div>
			<div class="comment-time">
				<span class="author vcard"><a class="url fn n" rel="author" href="<?php echo get_comment_author_url(); ?>" title="<?php echo get_comment_author(); ?> <?php _e( 'Says', 'tokopress' ); ?>"><?php echo get_comment_author(); ?></a></span>  
				<time class="published" datetime="<?php echo get_comment_date( 'c' ); ?>" title="<?php echo get_comment_date( 'l, F jS, Y, g:i a' ); ?>"><?php echo get_comment_date(); ?></time> <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'tokopress' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
		<div itemprop="description" class="comment-text">
			<?php comment_text(); ?>
		</div>
	</li>

	<?php if ( $comment->comment_approved == '0' ) : ?>

        <div class="alert alert-moderation">
            <p><em><?php _e( 'Your comment is awaiting moderation.', 'tokopress' ); ?></em></p>
        </div>

    <?php endif;

}

/**
 * Pagination
 */
add_action( 'tokopress_after_content', 'tokopress_paging_nav', 50 );
if ( ! function_exists( 'tokopress_paging_nav' ) ) {
	function tokopress_paging_nav() {
		
		global $wp_query;
		
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		
		?>

		<div class="col-md-12">
			<nav class="pagination clearfix">

				<?php
				
				echo paginate_links( array(
					'base' 			=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
					'format' 		=> '',
					'current' 		=> max( 1, get_query_var( 'paged' ) ),
					'total' 		=> $wp_query->max_num_pages,
					'prev_text' 	=> __( 'Prev', 'tokopress' ),
					'next_text' 	=> __( 'Next', 'tokopress' ),
					'type'			=> 'plain',
					'end_size'		=> 3,
					'mid_size'		=> 3
				) );
				
				?>
				
			</nav><!-- End .pagination for-product -->
		</div>
		
		<?php

	}
}

/**
 * Header script
 */
function tokopress_header_script() {
	echo of_get_option( 'tokopress_header_script' );
}
add_action( 'wp_head', 'tokopress_header_script', 999 );

/**
 * Footer script
 */
function tokopress_footer_script() {
	echo of_get_option( 'tokopress_footer_script' );
}
add_action( 'wp_footer', 'tokopress_footer_script', 999 );


/**
 * Custom Background Callback
 */
function tokopress_custom_background_cb() {
	// $background is the saved custom image, or the default image.
	$background = set_url_scheme( get_background_image() );

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = get_background_color();

	if ( $color === get_theme_support( 'custom-background', 'default-color' ) ) {
		$color = false;
	}

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat_css = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position_css = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment_css = " background-attachment: $attachment;";

		$style .= $image . $repeat_css . $position_css . $attachment_css;

		if ( $attachment == 'fixed' && $repeat == 'no-repeat' ) {
			$style .= " background-size: cover;";			
		}

	}
?>
<style type="text/css" id="custom-background-css">
body.custom-background { <?php echo trim( $style ); ?> }
</style>
<?php
}
