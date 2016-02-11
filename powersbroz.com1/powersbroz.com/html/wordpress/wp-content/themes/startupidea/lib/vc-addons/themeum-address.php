<?php
add_shortcode( 'themeum_address', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'email' 	=> '',
		'number'	=> '',
		'place'		=> '',
		'website'	=> '',
		'fax'		=> '',
		'padding'	=> '20px 20px 20px 20px',
		'bg_color'	=> '#f7f8fa',
		'class'		=> ''
		), $atts));

	$style ='';


	if($padding) $style .= 'padding:' . esc_attr($padding ).';';
	if($bg_color) $style .= 'background:' . esc_attr($bg_color)  . ';';

	$output = '';

	$output .= '<div class="themeum-address  '.esc_attr($class).'" style="'.$style.'">';
    $output .= '<ul class="list-unstyled details">';
    if($email) { $output .= '<li><i class="fa fa-envelope"></i>'.esc_attr($email).'</li>'; }
    if($website) { $output .= '<li><i class="fa fa-globe"></i>'.esc_attr($website).'</li>'; }
    if($number) { $output .= '<li><i class="fa fa-phone"></i>'.esc_attr($number).'</li>'; }
    if($fax) { $output .= '<li><i class="fa fa-fax"></i>'.esc_attr($fax).'</li>'; }
    if($place) { $output .= '<li><i class="fa fa-globe"></i>'.balanceTags($place).'</li>'; }
    $output .= '</ul>';
	$output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Address", "themeum"),
	"base" => "themeum_address",
	'icon' => 'icon-thm-address',
	"class" => "",
	"description" => __("Widget Title Heading", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(
			

		array(
			"type" => "textfield",
			"heading" => __("Email", "themeum"),
			"param_name" => "email",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Phone Number", "themeum"),
			"param_name" => "number",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Website", "themeum"),
			"param_name" => "website",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Fax", "themeum"),
			"param_name" => "fax",
			"value" => "",
			),									

		array(
			"type" => "textarea",
			"heading" => __("Place", "themeum"),
			"param_name" => "place",
			"value" => "",
			),					
		
		array(
			"type" => "textfield",
			"heading" => __("Padding", "themeum"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "colorpicker",
			"heading" => __("Background Color", "themeum"),
			"param_name" => "bg_color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "themeum"),
			"param_name" => "class",
			"value" => "",
			),

		)
	));
}