<?php
add_shortcode( 'themeum_partners', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'company_name' 		=> '',
		'company_url'		=> 'www.themeum.com',	
		'class'				=> '',
		'image'				=> ''
		), $atts));

	$src_image   = wp_get_attachment_image_src($image, 'full');


	$output = '';

	$output .= '<div class="partners '.esc_attr($class).'">';
	        $output .= '<a href="'.esc_url($company_url).'" target="_blank"><img src="'.esc_url($src_image[0]).'" alt="partners"></a>';
	$output .= '</div>';




	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Partners", "themeum"),
	"base" => "themeum_partners",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Featured Title Heading", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

		array(
			"type" => "attach_image",
			"heading" => __("Insert Image", "themeum"),
			"param_name" => "image",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Company URL", "themeum"),
			"param_name" => "company_url",
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