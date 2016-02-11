<?php
/**
 * Tokopress Search Events
 *
 * @package Events
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_events_search', 'eventica_shortcode_events_search' );
function eventica_shortcode_events_search( $atts ) {

	if( ! class_exists( 'Tribe__Events__Main' ) )
		return;

	extract( shortcode_atts( array(
		'placeholder'	=> '',
	), $atts ) );

	if( !$placeholder ) {
		$placeholder = __( 'Search Events &hellip;', 'tokopress' );
	}

	$output = '';
	$output .= '<div class="home-search-box">';

		$output .= '<div class="row">';
			$output .= '<div class="col-md-12">';

				$output .= '<form role="search" class="search-form" name="tribe-bar-form" method="post" action="' . tribe_get_events_link() . '">';
					$output .= '<input type="hidden" name="tribe-bar-date" value="">';
					$output .= '<input type="hidden" name="tribe-bar-date-day" value="">';
					$output .= '<label>';
						$output .= '<span class="screen-reader-text">' . __( 'Search for:', 'tokopress' ) . '</span>';
						$output .= '<input type="search" class="search-field" placeholder="' . $placeholder . '" value="' . get_search_query() . '" name="tribe-bar-search" title="" />';
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

add_action( 'vc_before_init', 'eventica_vc_events_search' );
function eventica_vc_events_search() {
	vc_map( array(
	   'name'				=> __( 'Eventica - Events Search Form', 'tokopress' ),
	   'base'				=> 'eventica_events_search',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Placeholder text', 'tokopress' ),
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Search Events &hellip;', 'tokopress' ),
									'param_name'	=> 'placeholder',
									'value'			=> ''
								),
							)
		)
	);
}