<?php
add_shortcode( 'themeum_feature_items', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'image'				=> '',
		'animation'			=> 'fadeInLeft',
		'duration' 			=> '800',  
        'delay' 			=> '200',
		'align'				=> 'text-left',
		'title'				=> '',
		'title_size'		=> '18',
		'text'				=> '',
		'text_size'			=> '16',
		'color'				=> '#6f797a',
		'class'				=> '',
		), $atts));

	$src_image   = wp_get_attachment_image_src($image, 'full');

	$dur = '';
	$item_del = '';

	if ($duration) $dur .= (int) esc_attr($duration) .'ms'; 
 	if ($delay) $item_del .= (int) esc_attr($delay).'ms'; 


		$output = $style = $style2 = '';

		if($title_size != '' ){ $style .= 'font-size:'.esc_attr($title_size).'px; color:'.esc_attr($color).';'; }
		if($text_size != '' ){ $style2 .= 'font-size:'.esc_attr($text_size).'px; color:'.esc_attr($color).';'; }


		if($animation  != '' ){ 
			$output .= '<div class="themeum-feature-item">';
			$output .= '<div class="wow '.esc_attr($class).' '.esc_attr($animation).' '.esc_attr($align).'" data-wow-duration="'.$dur.'" data-wow-delay="'.$item_del.'">';	
		}else{
			$output .= '<div class="themeum-feature-item">';
			$output .= '<div class="wow '.esc_attr($align).'">';	
		}
			$output .= '<div class="icon"><img src="'.esc_url($src_image[0]).'" alt="icon"></div>';
			$output .= '<h4 style="'.$style.'">'.balanceTags($title).'</h4>';
			$output .= '<p style="'.$style2.'">'.esc_html($text).'</p>';
			$output .= '</div>';
		$output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Feature Items", "themeum"),
	"base" => "themeum_feature_items",
	'icon' => 'icon-thm-image-caption',
	"description" => __("Feature Items is Display Here.", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

		array(
			"type" => "attach_image",
			"heading" => __("Insert Image", "themeum"),
			"param_name" => "image",
			"value" => "",
			),

		array(
			"type" => "dropdown",
			"heading" => __("Animation", "themeum"),
			"param_name" => "animation",
			"value" => array('Select'=>'','No Style'=>'','Fade in Left'=>'fadeInLeft','Fade in Down'=>'fadeInDown','Fade in Right'=>'fadeInRight','Fade in Up'=>'fadeInUp'),
			),	
		array(
			"type" => "textfield",
			"heading" => __("Animation Duration", "themeum"),
			"param_name" => "duration",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Animation Delay", "themeum"),
			"param_name" => "delay",
			"value" => "",
			),			

		array(
			"type" => "dropdown",
			"heading" => __("Text Align", "themeum"),
			"param_name" => "align",
			"value" => array('Select'=>'','Left'=>'text-left','Center'=>'text-center','Right'=>'text-right'),
			),			

		array(
			"type" => "textfield",
			"heading" => __("Title", "themeum"),
			"param_name" => "title",
			"value" => ""
			),	

		array(
			"type" => "textfield",
			"heading" => __("Title Font Size", "themeum"),
			"param_name" => "title_size",
			"value" => ""
			),	

		array(
			"type" => "colorpicker",
			"heading" => __("Title Color", "themeum"),
			"param_name" => "color",
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
			"heading" => __("Text Font Size", "themeum"),
			"param_name" => "text_size",
			"value" => ""
			),	

		array(
			"type" => "textfield",
			"heading" => __("Custom Class", "themeum"),
			"param_name" => "class",
			"value" => ""
			),	

		)
	));
}