<?php

/**
 * Output Breadcrumb
 */
function tokopress_breadcrumb( $args = array() ) {
	echo tokopress_get_breadcrumb( $args );
}

/**
 * Return breadcrumb. */
function tokopress_get_breadcrumb( $args = array() ) {
	$defaults = array(
		'container'       => 'div',
		'separator'       => '&#47;',
		'before'          => '',
		'after'           => '',
		'show_on_front'   => false,
		'network'         => false,
		'show_title'      => true,
		'show_browse'     => false,
		'echo'            => true,
		'post_taxonomy'   => array(
			'post'        => 'category',
		),
	);

	$args = apply_filters( 'toko_breadcrumb_args', wp_parse_args( $args, $defaults ) );
	
	if ( is_singular('post') || is_category() || is_tag() ) {
		$breadcrumb = new blog_Breadcrumb_Trail( $args );
	}
	elseif ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
		$breadcrumb = new bbPress_Breadcrumb_Trail( $args );
	}
	else {
		$breadcrumb = new Breadcrumb_Trail( $args );
	}

	return $breadcrumb->trail();
}

class blog_Breadcrumb_Trail extends Breadcrumb_Trail {

	public function do_trail_items() {

		$this->do_network_home_link();
		$this->do_site_home_link();

		$this->do_blog_link();

		/* If viewing a single post. */
		if ( is_singular() ) {
			$this->do_singular_items();
		}
		elseif ( is_category() || is_tag() ) {
			$this->do_term_archive_items();
		}
		/* If viewing the 404 page. */
		elseif ( is_404() ) {
			$this->do_404_items();
		}

		/* Add paged items if they exist. */
		$this->do_paged_items();

		/* Allow developers to overwrite the items for the breadcrumb trail. */
		$this->items = apply_filters( 'breadcrumb_trail_items', $this->items, $this->args );
	}

	public function do_blog_link() {
		if ( get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
			$id = get_option('page_for_posts');
			$blog_url = get_permalink( $id );
			$blog_title = get_the_title( $id );
			$this->items[] = '<a href="' . $blog_url . '" title="' . esc_attr( $blog_title ) . '">' . $blog_title . '</a>';
		}
	}

}

/**
 * Allow to remove method for an hook when, it's a class method used and class don't have global for instanciation !
 * @link https://github.com/herewithme/wp-filters-extras
 * @author BeAPI http://www.beapi.fr
 */
function tokopress_remove_filter( $hook_name = '', $method_name = '', $priority = 0 ) {
	global $wp_filter;
	
	// Take only filters on right hook name and priority
	if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
		return false;
	
	// Loop on filters registered
	foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
			// Test if object is a class and method is equal to param !
			if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && $filter_array['function'][1] == $method_name ) {
				unset($wp_filter[$hook_name][$priority][$unique_id]);
			}
		}
		
	}
	
	return false;
}

/**
 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name :)
 * @link https://github.com/herewithme/wp-filters-extras
 * @author BeAPI http://www.beapi.fr
 */
function tokopress_remove_filter_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
	global $wp_filter;
	
	// Take only filters on right hook name and priority
	if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
		return false;
	
	// Loop on filters registered
	foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
			// Test if object is a class, class and method is equal to param !
			if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
				unset($wp_filter[$hook_name][$priority][$unique_id]);
			}
		}
		
	}
	
	return false;
}