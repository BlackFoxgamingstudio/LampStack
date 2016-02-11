<?php
add_shortcode( 'themeum_call_to_action', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'title' 					=> '',
		'title_weight' 				=> '400',
		'title_size' 				=> '24',
		'title_color' 				=> '#6f797a',
		'title_margin' 				=> '10px 0px 5px 0px',
		'btn_text'					=> '',
		'btn_url'					=> '',
		'btn_bg'					=> '#8560a8',
		'btn_bg_hover'				=> '#8560a8',
		'btn_size'					=> '16',
		'btn_color'					=> '#fff',
		'btn_color_hover'			=> '#fff',
		'btn_margin'				=> '10px 0px 5px 0px',
		'btn_padding'				=> '10px 0px 5px 0px',
		'subtitle'					=> '',
		'subtitle_size'				=> '16',
		'subtitle_weight'			=> '400',
		'subtitle_lh'				=> '',
		'subtitle_color'			=> '#6f797a',
		'position'					=> 'right',
		'class'						=> '',
		), $atts));


	$font_size = '';
	$font_size2 = '';
	$btnstype = '';
	$style = '';


	if($title_size) $font_size .= 'font-size:' . (int) esc_attr($title_size) . 'px;line-height:' . (int) esc_attr($title_size) . 'px;';
	if($title_color) $font_size .= 'color:' .  esc_attr($title_color) . ';';
	if($title_margin) $font_size .= 'margin:' .  esc_attr($title_margin) . ';';
	if($title_weight) $font_size .= 'font-weight:'. esc_attr($title_weight) .';';

	if($btn_margin) $btnstype .= 'margin:' .  esc_attr($btn_margin) . ';display:inline-block;';
	if($btn_padding) $btnstype .= 'padding:' .  esc_attr($btn_padding) . ';';
	if($btn_color) $btnstype .= 'color:' .  esc_attr($btn_color) . ';';
	if($btn_bg) $btnstype .= 'background:' .  esc_attr($btn_bg) . ';';
	if($btn_size) $btnstype .= 'font-size:' . (int) esc_attr($btn_size) . 'px;';

	if($subtitle_size) $font_size2 .= 'font-size:' . (int) esc_attr($subtitle_size) . 'px;';
	if($subtitle_color) $font_size2 .= 'color:' .  esc_attr($subtitle_color) . ';';
	if($subtitle_weight) $font_size2 .= 'font-weight:'. esc_attr($subtitle_weight) .';';
	if($subtitle_lh) $font_size2 .= 'line-height:'. (int)esc_attr($subtitle_lh). 'px;';

	$output = '';

	switch ($position) {

        case 'right':
        	    $output .= '<div class="themeum-action-shortcode '.esc_attr($class).'">';
				$output .= '<div class="row">';

				$output .= '<div class="col-sm-8">';
				if ($title)
    			{
					$output .= '<h3 class="themeum-action-title" style="' . $font_size . '">' . esc_attr($title) . '</h3>';
				}
				if ($subtitle)
    			{
					$output .= '<span class="themeum-action-subtitle" style="' . $font_size2 . '">' . balanceTags($subtitle) . '</span>';
				}
				$output .= '</div>';

				$output .= '<div class="col-sm-4">';
				$output .= '<div class="text-right">';
				if ($btn_url)
				{
					$output .= '<a class="thm-color acton-btn" data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_bg_hover).'" style="'.$btnstype.'" href="' . esc_url($btn_url) . '">' . esc_attr($btn_text) . '</a>';
				}
				$output .= '</div>';
				$output .= '</div>';				

				$output .= '</div>';
				$output .= '</div>';
            break; 

        case 'center':
        	    $output .= '<div class="themeum-action-shortcode '.esc_attr($class).'">';
				$output .= '<div class="themeum-action-center text-center">';	
				if ($title)
    			{
					$output .= '<h3 class="themeum-action-title" style="' . $font_size . '">' . esc_attr($title) . '</h3>';
				}
				if ($subtitle)
    			{
					$output .= '<p class="themeum-action-subtitle" style="' . $font_size2 . '">' . balanceTags($subtitle) . '</p>';
				}
				if ($btn_url)
				{
					$output .= '<a class="thm-color acton-btn" data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_bg_hover).'"  style="'.$btnstype.'" href="' . esc_url($btn_url) . '">' . esc_attr($btn_text) . '</a>';
				}
				$output .= '</div>';
				$output .= '</div>';
            break;
         
        default:
        	     $output .= '<div class="themeum-action-shortcode '.esc_attr($class).'">';
				$output .= '<div class="row">';

				$output .= '<div class="col-sm-8">';
				if ($title)
    			{
					$output .= '<h3 class="themeum-action-title" style="' . $font_size . '">' . esc_attr($title) . '</h3>';
				}
				if ($subtitle)
    			{
					$output .= '<span class="themeum-action-subtitle" style="' . $font_size2 . '">' . balanceTags($subtitle) . '</span>';
				}
				$output .= '</div>';

				$output .= '<div class="col-sm-4">';
				$output .= '<div class="text-right">';
				if ($btn_url)
				{
					$output .= '<a class="thm-color acton-btn" data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_bg_hover).'"  style="'.$btnstype.'" href="' . esc_url($btn_url) . '">' . esc_attr($btn_text) . '</a>';
				}
				$output .= '</div>';
				$output .= '</div>';				

				$output .= '</div>';
				$output .= '</div>';
            break;
    }

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Call to action", "themeum"),
	"base" => "themeum_call_to_action",
	'icon' => 'icon-thm-call-to-action',
	"class" => "",
	"description" => __("Call to action shortcode.", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Button Position", "themeum"),
			"param_name" => "position",
			"value" => array('Select'=>'','Right'=>'right','Center'=>'center'),
			),			

		array(
			"type" => "textfield",
			"heading" => __("Title", "themeum"),
			"param_name" => "title",
			"value" => "Call to action title",
			"admin_label"=>true,
			),

		array(
			"type" => "textfield",
			"heading" => __("Title Font Size", "themeum"),
			"param_name" => "title_size",
			"value" => "",
			),	
		
		array(
			"type" => "dropdown",
			"heading" => __("Title Font Wight", "themeum"),
			"param_name" => "title_weight",
			"value" => array('Select'=>'','400'=>'400','100'=>'100','200'=>'200','300'=>'300','500'=>'500','600'=>'600','700'=>'700'),
			),	

		array(
			"type" => "colorpicker",
			"heading" => __("Title Color", "themeum"),
			"param_name" => "title_color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Title Margin Ex. 10px 0 5px 0", "themeum"),
			"param_name" => "title_margin",
			"value" => "",
			),			

		array(
			"type" => "textarea",
			"heading" => __("Sub Title", "themeum"),
			"param_name" => "subtitle",
			"value" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et dignissim"
			),

		array(
			"type" => "textfield",
			"heading" => __("Sub Title Font Size", "themeum"),
			"param_name" => "subtitle_size",
			"value" => "",
			),

		array(
			"type" => "dropdown",
			"heading" => __("Sub Title Font Wight", "themeum"),
			"param_name" => "subtitle_weight",
			"value" => array('Select'=>'','400'=>'400','100'=>'100','200'=>'200','300'=>'300','500'=>'500','600'=>'600','700'=>'700'),
			),	

		array(
			"type" => "textfield",
			"heading" => __("Sub Title Line Height", "themeum"),
			"param_name" => "subtitle_lh",
			"value" => "",
			),							

		array(
			"type" => "colorpicker",
			"heading" => __("Sub Title Color", "themeum"),
			"param_name" => "subtitle_color",
			"value" => "",
			),	

		array(
			"type" => "textfield",
			"heading" => __("Button Text", "themeum"),
			"param_name" => "btn_text",
			"value" => "Button"
			),

		array(
			"type" => "textfield",
			"heading" => __("Button Font Size", "themeum"),
			"param_name" => "btn_size",
			"value" => "",
			),		

		array(
			"type" => "colorpicker",
			"heading" => __("Button Background", "themeum"),
			"param_name" => "btn_bg",
			"value" => "",
			),

		array(
			"type" => "colorpicker",
			"heading" => __("Hover Button Background", "themeum"),
			"param_name" => "btn_bg_hover",
			"value" => "",
			),

		array(
			"type" => "colorpicker",
			"heading" => __("Button Color", "themeum"),
			"param_name" => "btn_color",
			"value" => "",
			),	

		array(
			"type" => "colorpicker",
			"heading" => __("Hover Button Color", "themeum"),
			"param_name" => "btn_color_hover",
			"value" => "",
			),					

		array(
			"type" => "textfield",
			"heading" => __("Button Url", "themeum"),
			"param_name" => "btn_url",
			"value" => ""
			),

		array(
			"type" => "textfield",
			"heading" => __("Button Margin Ex. 10px 0 5px 0", "themeum"),
			"param_name" => "btn_margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Button padding Ex. 10px 0 5px 0", "themeum"),
			"param_name" => "btn_padding",
			"value" => "",
			),


		array(
			"type" => "textfield",
			"heading" => __("Class", "themeum"),
			"param_name" => "class",
			"value" => ""
			),		

		)
	));
}