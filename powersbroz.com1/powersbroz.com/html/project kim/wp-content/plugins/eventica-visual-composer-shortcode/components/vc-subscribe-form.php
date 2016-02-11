<?php
/**
 * Tokopress Subscribe Form
 *
 * @package MailChimp
 * @author Tokopress
 *
 */

add_shortcode( 'eventica_subscribe_form', 'tokopress_subscribe_form_sortcode' );
function tokopress_subscribe_form_sortcode( $atts ) {

	if( !( defined( 'MC4WP_VERSION' ) || class_exists( "MC4WP_Lite" ) ) )
		return;

	extract( shortcode_atts( array(
		'title'			=> '',
		'description'	=> ''
	), $atts ) );

	if ( !trim($title) )
		$title = __( 'Subscribe to our newsletter', 'tokopress' );

	if ( !trim($description) )
		$description = __( 'never miss our latest news and events updates', 'tokopress' );

	$output = '';
	$output .= '<div class="home-subscribe-form clearfix">';
		$output .= '<div class="row">';
			$output .= '<div class="col-md-7">';
				$output .= '<h2>';
					$output .= $title;
				$output .= '</h2>';
				$output .= '<p>';
					$output .= $description;
				$output .= '</p>';
			$output .= '</div>';
			$output .= '<div class="col-md-5">';
				/* NOTE: currently using do_shortcode is the best way to output subscribe form*/
				/* TODO: contact mailchimp4wp plugin developer to add direct function call to output subscibe form */
				$output .= do_shortcode( '[mc4wp_form]' );
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';

	return $output;

}

add_action( 'vc_before_init', 'eventica_vc_subscribe_form' );
function eventica_vc_subscribe_form() {

	if( !( defined( 'MC4WP_VERSION' ) || class_exists( "MC4WP_Lite" ) ) )
		return;

	vc_map( array(
	   'name'				=> __( 'Eventica - Subscribe Form', 'tokopress' ),
	   'base'				=> 'eventica_subscribe_form',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'Eventica',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Title', 'tokopress' ),
									'param_name'	=> 'title',
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'Subscribe to our newsletter', 'tokopress' ),
									'value'			=> ''
								),
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Description', 'tokopress' ),
									'param_name'	=> 'description',
									'description'	=> __( 'Default:', 'tokopress' ).' '.__( 'never miss our latest news and events updates', 'tokopress' ),
									'value'			=> ''
								),
							)
	   )
	);
}