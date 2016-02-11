<?php
/**
 * Tokopress Testimonial
 *
 * @package VC Element
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_testimonials', 'tokopress_shortcode_testimonials' );
function tokopress_shortcode_testimonials( $atts ) {

	if ( !function_exists( 'register_tokopress_testimonial' ) )
		return;

	extract( shortcode_atts( array(
		'numbers'			=> '',
		'title_hide'		=> 'no',
		'title_text'		=> '',
		'title_color'		=> '',
	), $atts ) );

	$numbers = intval( $numbers );
	if ( $numbers < 1 )
		$numbers = '-1';

	if ( !trim($title_text) )
		$title_text = __( 'Testimonials', 'tokopress' );

	$title_style = trim( $title_color ) ? 'style="color:'.$title_color.'"' : '';

	$args = array(
		'post_status'			=> 'publish',
		'post_type'				=> 'testimonial',
		'posts_per_page'		=> 4,
		'orderby'				=> 'date',
		'order'					=> 'DESC',
		'ignore_sticky_posts' 	=> true
		);
	$the_testimonial = new WP_Query( $args );

	$output = '';
	if( $the_testimonial->have_posts() ) :
		$output .= '<div class="home-testimonials">';
			$output .= '<div class="testimonial-wrap">';
				if ( $title_hide != 'yes' ) :
					$output .= '<div class="testimonial-title">';
					$output .= '<h2>'.$title_text.'</h2>';
					$output .= '</div>';
				endif;
				$output .= '<div class="testimonial-loop">';
				while ( $the_testimonial->have_posts() ) :
					$the_testimonial->the_post();
					$output .= '<div class="testimonial-field">';
						$output .= '<div class="testimonial-content">';
							$output .= get_the_content();
						$output .= '</div>';
						$output .= '<div class="testimonial-name">';
							$output .= get_the_title();
						$output .= '</div>';
					$output .= '</div>';
				endwhile;
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		if ( $the_testimonial->post_count > 1 ) {
			wp_enqueue_script( 'tokopress-owl-js' );
		}
	endif;
	wp_reset_postdata();

	return $output;
}


add_action( 'vc_before_init', 'eventica_vc_testimonials' );
function eventica_vc_testimonials() {

	if ( !function_exists( 'register_tokopress_testimonial' ) )
		return;

	vc_map( array(
	   'name'				=> __( 'Eventica - Testimonials', 'tokopress' ),
	   'base'				=> 'eventica_testimonials',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
	   							array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Number Of Testimonials', 'tokopress' ),
									'param_name'	=> 'numbers',
									'value'			=> array(
														''		=> '',
														'1'		=> '1',
														'2'		=> '2',
														'3'		=> '3',
														'4'		=> '4',
														'5'		=> '5',
														'6'		=> '6',
														'7'		=> '7',
														'8'		=> '8',
														'9'		=> '9',
														'10'	=> '10',
													),
									'std'			=> '',
								),
								array(
									'type'			=> 'dropdown',
									'heading'		=> __( 'Hide Section Title', 'tokopress' ),
									'param_name'	=> 'title_hide',
									'value'			=> array(
														__( 'No', 'tokopress' ) => 'no',
														__( 'Yes', 'tokopress' ) => 'yes',
													),
								),
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Section Title Text', 'tokopress' ),
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Testimonials', 'tokopress' ),
									'param_name'	=> 'title_text',
									'value'			=> ''
								),
								array(
									'type' 			=> 'colorpicker',
									'heading' 		=> __( 'Section Title Color', 'tokopress' ),
									'param_name' 	=> 'title_color',
									'description' 	=> __( 'Select text color for section title.', 'tokopress' )
								),
							)
	   )
	);
}
