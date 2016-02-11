<?php
/**
 * Functions to handle post-related functions
 *
 * WARNING: This file is part of the Jewelrica parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   Jewelrica
 * @package    Theme Functions
 * @subpackage Contact Form
 * @author     TokoPress
 * @link       http://www.tokopress.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Return post date (inside loop)
 */
function tokopress_get_post_date( $args = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'format' => get_option( 'date_format' ) );
	$args = wp_parse_args( $args, $defaults );
	
	$date = '<time class="post-time" datetime="' . get_the_time( 'c' ) . '">';
	$date .= $args['before'];
	$date .= get_the_time( $args['format'] );
	$date .= $args['after'];
	$date .= '</time>';
	
	return apply_filters( 'tokopress_post_date', $date );
}

/**
 * Return post author (inside loop)
 */
function tokopress_get_post_author( $args = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = wp_parse_args( $args, $defaults );
	
	$author = '<span class="post-author">';
	$author .= get_avatar( get_the_author_meta( 'ID' ) );
	$author .= $args['before'];
	$author .= '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" class="post-author-link" rel="author">';
	$author .= '<span class="post-author-name">' . get_the_author() . '</span>';
	$author .= '</a>';
	$author .= $args['after'];
	$author .= '</span>';
	
	return apply_filters( 'tokopress_post_author', $author );
}

/**
 * Return post comments link (inside loop)
 */
function tokopress_get_post_comments_link( $args = array() ) {
	$defaults = array( 'zero' => __( 'Leave a comment', 'tokopress' ), 'one' => __( '1 Comment', 'tokopress' ), 'more' => __( '%1$s Comments', 'tokopress' ), 'css_class' => 'comments-link', 'none' => '', 'before' => '', 'after' => '' );
	$args = wp_parse_args( $args, $defaults );
	
	$number = get_comments_number();
	$comments_link = '';
	if ( 0 == $number && !comments_open() && !pings_open() ) {
		if ( $args['none'] )
			$comments_link = '<span class="' . esc_attr( $args['css_class'] ) . '">' . $args['none'] . '</span>';
	}
	elseif ( $number == 0 )
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . get_permalink() . '#respond" title="' . sprintf( __( 'Comment on %1$s', 'tokopress' ), the_title_attribute( 'echo=0' ) ) . '">' . $args['zero'] . '</a>';
	elseif ( $number == 1 )
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', 'tokopress' ), the_title_attribute( 'echo=0' ) ) . '">' . $args['one'] . '</a>';
	elseif ( $number > 1 )
		$comments_link = '<a class="' . esc_attr( $args['css_class'] ) . '" href="' . get_comments_link() . '" title="' . sprintf( __( 'Comment on %1$s', 'tokopress' ), the_title_attribute( 'echo=0' ) ) . '">' . sprintf( $args['more'], $number ) . '</a>';
	if ( $comments_link )
		$comments_link = '<span class="post-comment">' . $args['before'] . $comments_link . $args['after'] . '</span>';
		
	return apply_filters( 'tokopress_post_comments_link', $comments_link );
}

/**
 * Return post edit link (inside loop)
 */
function tokopress_get_post_edit_link( $args = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = wp_parse_args( $args, $defaults );
	
	global $post;
	$post_type = get_post_type_object( $post->post_type );
	if ( !current_user_can( "edit_{$post_type->capability_type}", $post->ID ) )
		return '';
		
	$edit = '<span class="post-edit">' . $args['before'] . '<a class="post-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . sprintf( __( 'Edit %1$s', 'tokopress' ), $post->post_type ) . '">' . __( 'Edit', 'tokopress' ) . '</a>' . $args['after'] . '</span>';
	
	return apply_filters( 'tokopress_post_edit_link', $edit );
}

/**
 * Return post terms (inside loop)
 */
function tokopress_get_post_terms( $args = array() ) {
	$defaults = array( 'id' => get_the_ID(), 'taxonomy' => 'post_tag', 'separator' => ', ', 'before' => '', 'after' => '' );
	$args = wp_parse_args( $args, $defaults );
	
	$args['before'] = '<span class="post-terms post-' . $args['taxonomy'] . '">' . $args['before'];
	$args['after'] .= '</span>';
	$terms = get_the_term_list( $args['id'], $args['taxonomy'], $args['before'], $args['separator'], $args['after'] );
	
	return apply_filters( 'tokopress_post_terms', $terms );
}

/**
 * Return post shortlink (inside loop)
 */
function tokopress_get_post_shortlink( $args = array() ) {
	$defaults = array( 'text' => __( 'Shortlink', 'tokopress' ), 'title' => the_title_attribute( array( 'echo' => false ) ), 'before' => '', 'after' => '' );
	$args = wp_parse_args( $args, $defaults );
	
	global $post;
	$shortlink = wp_get_shortlink( $post->ID );
	$shortlink = '<span class="post-shortlink">' . $args['before'] . '<a class="shortlink" href="' . $shortlink . '" title="' . $args['title'] .'" rel="shortlink">' . $args['text'] . '</a>' . $args['after'] . '</span>';
	
	return apply_filters( 'tokopress_post_shortlink', $edit );
}

/**
 * Return post navigation.
 */
function tokopress_get_post_navigation( $args = array() ) {
	global $wp_query;
	
	$defaults = array( 'query' => $wp_query, 'type' => 'prevnext' );
	$args = wp_parse_args( $args, $defaults );
	
	if( $args['query']->max_num_pages <= 1 ) return;

	$nav = '';
	if ( $args['type'] == 'prevnext' ) {
		$prev_link = get_previous_posts_link( __( '&larr; Previous', 'tokopress' ) );
		$nav .= $prev_link ? '<div class="alignleft">' . $prev_link . '</div>' : '';
		
		$next_link = get_next_posts_link( __( 'Next &rarr;', 'tokopress' ), $args['query']->max_num_pages );
		$nav .= $next_link ? '<div class="alignright">' . $next_link . '</div>' : '';
	}
	elseif ( $args['type'] == 'oldernewer' ) {
		$older_link = get_next_posts_link( __( '&larr; Older', 'tokopress' ), $args['query']->max_num_pages );
		$nav .= $older_link ? '<div class="alignleft">' . $older_link . '</div>' : '';

		$newer_link = get_previous_posts_link( __( 'Newer &rarr;', 'tokopress' ) );
		$nav .= $newer_link ? '<div class="alignright">' . $newer_link . '</div>' : '';
	}
	elseif ( $args['type'] == 'numeric' ) {
		$big = 999999999; // need an unlikely integer
		$nav .= paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $args['query']->max_num_pages,
			'type'			=> 'list',
			'prev_text' 	=> '&larr;',
			'next_text' 	=> '&rarr;',
		) );
	}
	
	if ( !empty( $nav ) ) {
		$nav = '<nav class="navigation navigation-'.$args['type'].' group"><h3 class="assistive-text">'.__( 'Post navigation', 'tokopress' ).'</h3>'.$nav.'</nav>';
	}
	
	return apply_filters( 'tokopress_post_navigation', $nav );
}

/**
 * Return formatted post meta.
 */
function tokopress_get_post_meta( $meta, $postid = '', $format = '' ) {
	if ( !$postid ) { 
		global $post;
		if ( null === $post ) 
			return false;
		else 
			$postid = $post->ID;
	}
	$meta_value = get_post_meta($postid, $meta, true);
	if ( !$meta_value ) 
		return false;
	$meta_value = wp_kses_stripslashes( wp_kses_decode_entities( $meta_value ) );
	if ( is_ssl() ) 
		$meta_value = str_replace("http://", "https://", $meta_value);
	if ( !$format ) 
		return $meta_value;
	else return str_replace("%meta%", $meta_value, $format);
}

/**
 * Echo formatted post meta.
 */
function tokopress_post_meta( $meta, $postid = '', $format = '' ) {
	echo tokopress_get_post_meta( $meta, $postid, $format );
}

?>