<?php
/**
 * Tokopress Search Form
 *
 * @package VC Element
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_search', 'eventica_shortcode_search' );
function eventica_shortcode_search( $atts ) {

	extract( shortcode_atts( array(
		'placeholder'	=> '',
	), $atts ) );

	if( !$placeholder ) {
		$placeholder = __( 'Search &hellip;', 'tokopress' );
	}

	$output = '';
	$output .= '<div class="home-search-box">';

		$output .= '<div class="row">';
			$output .= '<div class="col-md-12">';

				$output .= '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">';
					$output .= '<label>';
						$output .= '<span class="screen-reader-text">' . __( 'Search for:', 'tokopress' ) . '</span>';
						$output .= '<input type="search" class="search-field" placeholder="' . $placeholder . '" value="' . get_search_query() . '" name="s" title="" />';
					$output .= '</label>';
					$output .= '<button type="submit" class="search-submit">';
						$output .= '<i class="fa fa-search"></i>';
					$output .= '</button>';
				$output .= '</form>';

			$output .= '</div>';
		$output .= '</div>';

	$output .= '</div>';

	return $output;

}

add_action( 'vc_before_init', 'eventica_vc_search' );
function eventica_vc_search() {
	vc_map( array(
	   'name'				=> __( 'Eventica - Search Form', 'tokopress' ),
	   'base'				=> 'eventica_search',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Placeholder text', 'tokopress' ),
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Search &hellip;', 'tokopress' ),
									'param_name'	=> 'placeholder',
									'value'			=> ''
								),
							)
		)
	);
}
