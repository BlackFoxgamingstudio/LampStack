<?php
add_shortcode( 'themeum_heading', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'title' 		=> '',
		'color'			=> '#6f797a',
		'animation'		=> 'fadeInLeft',
		'size'			=> '36',
		'title_margin'	=> '20px 0px 60px 0px',
		'title_padding'	=> '0px 0px 0px 0px',
		'text'			=> '',
		'class'			=> '',
		), $atts));

	

	$inline_css = $output = '';

	if($color){ $inline_css .= 'color:'.esc_attr($color).';'; }
	if($size){ $inline_css .= 'font-size:'.esc_attr($size).'px;'; }
	if($title_margin){  $inline_css .= 'margin:'.esc_attr($title_margin).';';  }
	if($title_padding){  $inline_css .= 'padding:'.esc_attr($title_padding).';';  }


	$output .= '<div class="section-header '.esc_attr($class).' text-center wow '.esc_attr($animation).'">';
	    $output .= '<h2 style="'.$inline_css.'">'.esc_attr($title).'</h2>'; 
	    if($text){ $output .= '<p>'.balanceTags($text).'</p>'; }  
	$output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Heading", "themeum"),
	"base" => "themeum_heading",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Title Heading", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Animation", "themeum"),
			"param_name" => "animation",
			"value" => array('Select'=>'','No Style'=>'','Fade in Left'=>'fadeInLeft','Fade in Down'=>'fadeInDown','Fade in Right'=>'fadeInRight','Fade in Up'=>'fadeInUp'),
			),				

		array(
			"type" => "textfield",
			"heading" => __("Title", "themeum"),
			"param_name" => "title",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Font Size", "themeum"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Title Color", "themeum"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Title Margin", "themeum"),
			"param_name" => "title_margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Title Padding", "themeum"),
			"param_name" => "title_padding",
			"value" => "",
			),

		array(
			"type" => "textarea",
			"heading" => __("Text", "themeum"),
			"param_name" => "text",
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