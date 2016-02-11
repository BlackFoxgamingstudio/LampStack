<?php
/**
 * Tokopress Brand Sponsors
 *
 * @package VC Element
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_brand_sponsors', 'tokopress_shortcode_brand_sponsors' );
function tokopress_shortcode_brand_sponsors( $atts ) {

	extract( shortcode_atts( array(
		'title_hide'		=> 'no',
		'title_text'		=> '',
		'title_color'		=> '',
		'image_id_1'		=> '',
		'image_url_1'		=> '',
		'image_id_2'		=> '',
		'image_url_2'		=> '',
		'image_id_3'		=> '',
		'image_url_3'		=> '',
		'image_id_4'		=> '',
		'image_url_4'		=> '',
		'image_id_5'		=> '',
		'image_url_5'		=> '',
		'image_id_6'		=> '',
		'image_url_6'		=> '',
		'image_id_7'		=> '',
		'image_url_7'		=> '',
		'image_id_8'		=> '',
		'image_url_8'		=> '',
	), $atts ) );

	if ( !trim($title_text) )
		$title_text = __( 'Our Sponsors', 'tokopress' );

	$title_style = trim( $title_color ) ? 'style="color:'.$title_color.'"' : '';

	$output = '';
	$output .= '<div class="home-sponsors">';
		if ( $title_hide != 'yes' ) :
			$output .= '<h3 class="section-title">';
				$output .= $title_text;
			$output .= '</h3>';
		endif;
		$output .= '<div class="sponsors-inner clearfix">';
			for( $i=1; $i <= 8; $i++ ) {
				if ( isset( $atts["image_id_{$i}"] ) ) {
					$image_id = $atts["image_id_{$i}"];
					if ( $image_id ) {
						$image = wp_get_attachment_image( $image_id, 'full' );
						if ( $image ) {
							$output .= '<div class="sponsor">';
							$image_url = isset( $atts["image_url_{$i}"] ) ? esc_url( $atts["image_url_{$i}"] ) : '';
							if ( $image_url ) {
								$output .= '<a href="'.$image_url.'">'.$image.'</a>';
							}
							else {
								$output .= $image;
							}
							$output .= '</div>';
						}
					}
				}
			}
		$output .= '</div>';
	$output .= '</div>';
	return $output;
}

add_action( 'vc_before_init', 'eventica_vc_brand_sponsors' );
function eventica_vc_brand_sponsors() {

	$params = array(
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
	);
	
	for( $i=1; $i <= 8; $i++ ) {
		$params[] = array(
				'type'			=> 'attach_image',
				'heading'		=> sprintf( __( 'Image #%s', 'tokopress' ), $i ),
				'param_name'	=> 'image_id_'.$i,
			);
		$params[] = array(
				'type'			=> 'textfield',
				'heading'		=> sprintf( __( 'Image URL #%s', 'tokopress' ), $i ),
				'param_name'	=> 'image_url_'.$i,
			);
	}

	vc_map( array(
	   'name'				=> __( 'Eventica - Brand Sponsors', 'tokopress' ),
	   'base'				=> 'eventica_brand_sponsors',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}
