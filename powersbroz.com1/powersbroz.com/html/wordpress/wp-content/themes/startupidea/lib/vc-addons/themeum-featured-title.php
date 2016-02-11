<?php
add_shortcode( 'themeum_featured_title', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'title' 		=> '',
		'color'			=> '',
		'animation'		=> 'fadeInDown',
		'size'			=> '',
		'font_weight'	=> '700',
		'title2' 		=> 'Million people',
		'color2'		=> '#6f797a',
		'title_margin2' => '0px 0px 10px 0px',
		'size2'			=> '48',
		'title_margin'	=> '0 0 10px',
		'title_padding'	=> '0px 0px 0px 0px',
		'text'			=> '',
		'size3'			=> '16',
		'weight3'		=> '300',
		'lineheight3'	=> '',
		'color3'		=> '#6f797a',
		'class'			=> '',
		), $atts));

	
	$inline_css = $inline_css2 = $inline_css3 = $output = '';

	if($color){ $inline_css .= 'color:'.esc_attr($color).';'; }
	if($size){ $inline_css .= 'font-size:'.(int)esc_attr($size).'px;line-height:'.(int)esc_attr($size).'px;'; }
	if($title_margin){  $inline_css .= 'margin:'.esc_attr($title_margin).';';  }
	if($title_padding){  $inline_css .= 'padding:'.esc_attr($title_padding).';';  }

	if($color2){ $inline_css2 .= 'color:'.esc_attr($color2).';'; }
	if($size2){ $inline_css2 .= 'font-size:'.esc_attr($size2).'px;'; }
	if($title_margin2){  $inline_css2 .= 'margin:'.esc_attr($title_margin2).';';  }
	if($font_weight){  $inline_css2 .= 'font-weight:'.(int)esc_attr($font_weight).';';  }

	if($color3){ $inline_css3 .= 'color:'.esc_attr($color3).';'; }
	if($size3){ $inline_css3 .= 'font-size:'.(int)esc_attr($size3).'px;'; }
	if($lineheight3){ $inline_css3 .= 'line-height:'.(int)esc_attr($lineheight3).'px;'; }
	if($weight3){  $inline_css3 .= 'font-weight:'.(int)esc_attr($weight3).';';  }

	$output .= '<div class="feedback">';
		$output .= '<div class="'.esc_attr($animation).'">';
	        if($title){ $output .= '<p class="feature-text" style="'.$inline_css.'">'.esc_attr($title).'</p>';}
	        if($title2){ $output .= '<h4 style="'.$inline_css2.'">'.balanceTags($title2).'</h4>';}
	        if($text){ $output .= '<p style="'.$inline_css3.'">'.balanceTags($text).'</p>';}
	    $output .= '</div>';
    $output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Featured Title", "themeum"),
	"base" => "themeum_featured_title",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Featured Title Heading", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Animation", "themeum"),
			"param_name" => "animation",
			"value" => array('Select'=>'','No Animation'=>'','Fade in Down'=>'fadeInDown','Fade in Left'=>'fadeInLeft','Fade in Right'=>'fadeInRight','Fade in Up'=>'fadeInUp'),
			),		

		
		array(
			"type" => "textfield",
			"heading" => __("Digit", "themeum"),
			"param_name" => "title",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Digit Font Size", "themeum"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Digit Color", "themeum"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Digit Margin", "themeum"),
			"param_name" => "title_margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Digit Padding", "themeum"),
			"param_name" => "title_padding",
			"value" => "",
			),

		
		array(
			"type" => "textfield",
			"heading" => __("Title", "themeum"),
			"param_name" => "title2",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Title Font Size", "themeum"),
			"param_name" => "size2",
			"value" => "",
			),					

		array(
			"type" => "textfield",
			"heading" => __("Title Font Weight", "themeum"),
			"param_name" => "font_weight",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Title Color", "themeum"),
			"param_name" => "color2",
			"value" => "",
			),			


		array(
			"type" => "textfield",
			"heading" => __("Title Margin", "themeum"),
			"param_name" => "title_margin2",
			"value" => "",
			),


		array(
			"type" => "textarea",
			"heading" => __("Description", "themeum"),
			"param_name" => "text",
			"value" => "",
			),

		array(
			"type" => "colorpicker",
			"heading" => __("Description Color", "themeum"),
			"param_name" => "color3",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Description Font Size", "themeum"),
			"param_name" => "size3",
			"value" => "",
			),		

		array(
			"type" => "textfield",
			"heading" => __("Description Font Weight", "themeum"),
			"param_name" => "weight3",
			"value" => "",
			),						

		array(
			"type" => "textfield",
			"heading" => __("Description Line Height", "themeum"),
			"param_name" => "lineheight3",
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