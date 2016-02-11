<?php
add_shortcode( 'themeum_button', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'btn_size' 				=> 'medium',
		'btn_link' 				=> '',
		'btn_name'		 		=> '',
		'target'		 		=> '_self',
		'btn_text_size'			=> '14',
		'btn_color' 			=> '#62A83D',
		'btn_color_hover' 		=> '#62A83D',
		'btn_background' 		=> 'rgba(255,255,255,0)',
		'btn_background_hover' 	=> 'rgba(255,255,255,0)',
		'border_color' 			=> 'rgba(255, 255, 255, 0)',
		'border_color_hover' 	=> 'rgba(255, 255, 255, 0)',
		'border_width' 			=> '1',
		'border_style' 			=> 'none',
		'border_radius' 		=> '100',
		'btn_transform' 		=> 'capitalize',
		'btn_weight' 			=> '400',
		'btn_spacing' 			=> '0px',
		'btn_margin' 			=> '5px 0 5px 0',
		'btn_icon_position' 	=> 'none',
		'icon_name' 			=> '',
		'class' 				=> '',
		), $atts));

	$style = '';

	if($btn_text_size) $style .= 'font-size:' . (int) esc_attr($btn_text_size) . 'px;line-height:'. (int) esc_attr($btn_text_size)  .'px;';

	if($btn_color) $style .= 'color:' . esc_attr($btn_color)  . ';';

	if($btn_background) $style .= 'background:' . esc_attr($btn_background)  . ';';

	if($border_color) $style .= 'border-color:' . esc_attr($border_color)  . ';';

	if($border_width) $style .= 'border-width:' . (int) esc_attr($border_width)  . 'px;';

	if($border_style) $style .= 'border-style:' . esc_attr($border_style)  . ';';

	if($border_radius) $style .= 'border-radius:' . (int) esc_attr($border_radius)  . 'px;';

	if($btn_transform) $style .= 'text-transform:'. esc_attr($btn_transform) .';';
	
	if($btn_weight) $style .= 'font-weight:'. esc_attr($btn_weight) .';';

	if($btn_spacing) $style .= 'letter-spacing:'. esc_attr($btn_spacing) .';';

	if($btn_margin) $style .= 'margin:' . esc_attr($btn_margin)  . ';';


	$output = '';


    switch ($btn_icon_position) {
        case 'none':
	        if ($btn_link)
	        {
			$output .=  '<a data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_background_hover).'" data-hover-border-color="'.esc_attr($border_color_hover).'" class="thm-color themeum_button_shortcode '.$btn_size.' '.esc_attr($class).'" style="'.$style.'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name).'</a>';
	        }
            break;           

        case 'before':
        	if ($btn_link)
	        {
        	$output .=  '<a data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_background_hover).'" data-hover-border-color="'.esc_attr($border_color_hover).'" class="thm-color themeum_button_shortcode '.$btn_size.' '.esc_attr($class).'" style="'.$style.'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'"><i class="fa ' . esc_attr($icon_name) . '"></i>' .esc_attr($btn_name). '</a>';
            }  
            break;             

        case 'after':
            if ($btn_link)
	        {
        	$output .=  '<a data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_background_hover).'" data-hover-border-color="'.esc_attr($border_color_hover).'" class="thm-color themeum_button_shortcode '.$btn_size.' '.esc_attr($class).'" style="'.$style.'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name). '<i class="fa ' . esc_attr($icon_name) . '"></i></a>';
		    }
            break;   

        default:
	        if ($btn_link)
	        {
			$output .=  '<a  data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_background_hover).'" data-hover-border-color="'.esc_attr($border_color_hover).'" class="thm-color themeum_button_shortcode '.$btn_size.' '.esc_attr($class).'" style="'.$style.'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name).'</a>';
	        }
            break;
    }	

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => __("Button", "themeum"),
		"base" => "themeum_button",
		'icon' => 'icon-thm-btn',
		"category" => __('Themeum', "themeum"),
		"params" => array(


			array(
				"type" => "dropdown",
				"heading" => __("Buton Size", "themeum"),
				"param_name" => "btn_size",
				"value" => array('Select'=>'','Medium'=>'medium','Small'=>'small','Large'=>'large','Extra Large'=>'ex_large'),
				),				

			array(
				"type" => "textfield",
				"heading" => __("Link URL", "themeum"),
				"param_name" => "btn_link",
				"value" => "",
				),				

			array(
				"type" => "textfield",
				"heading" => __("Button Name", "themeum"),
				"param_name" => "btn_name",
				"value" => "",
				),	

			array(
				"type" => "dropdown",
				"heading" => __("Target Link", "themeum"),
				"param_name" => "target",
				"value" => array('Select'=>'','Self'=>'_self','Blank'=>'_blank','Parent'=>'_parent'),
				),								

			array(
				"type" => "textfield",
				"heading" => __("Button Text Size", "themeum"),
				"param_name" => "btn_text_size",
				"value" => "",
				),	

			array(
				"type" => "colorpicker",
				"heading" => __("Button Text Color", "themeum"),
				"param_name" => "btn_color",
				"value" => "",
				),

			array(
				"type" => "colorpicker",
				"heading" => __("Hover Button Text Color", "themeum"),
				"param_name" => "btn_color_hover",
				"value" => "",
				),	

			array(
				"type" => "colorpicker",
				"heading" => __("Button Background", "themeum"),
				"param_name" => "btn_background",
				"value" => "",
				),

			array(
				"type" => "colorpicker",
				"heading" => __("Hover Button Background", "themeum"),
				"param_name" => "btn_background_hover",
				"value" => "",
				),	

			array(
				"type" => "colorpicker",
				"heading" => __("Button Border Color", "themeum"),
				"param_name" => "border_color",
				"value" => "",
				),

			array(
				"type" => "colorpicker",
				"heading" => __("Hover Button Border Color", "themeum"),
				"param_name" => "border_color_hover",
				"value" => "",
				),				

			array(
				"type" => "textfield",
				"heading" => __("Border Width", "themeum"),
				"param_name" => "border_width",
				"value" => "",
				),	

			array(
				"type" => "dropdown",
				"heading" => __("Border Style", "themeum"),
				"param_name" => "border_style",
				"value" => array('Select'=>'','None'=>'none','Solid'=>'solid','Dashed'=>'dashed','Dotted'=>'dotted'),
				),			

			array(
				"type" => "textfield",
				"heading" => __("Border Radius", "themeum"),
				"param_name" => "border_radius",
				"value" => "",
				),	

			array(
				"type" => "dropdown",
				"heading" => __("Button Text Transform", "themeum"),
				"param_name" => "btn_transform",
				"value" => array('Select'=>'','Capitalize'=>'capitalize','Uppercase'=>'uppercase','Lowercase'=>'lowercase','None'=>'none'),
				),	

			array(
				"type" => "dropdown",
				"heading" => __("Button Font Wight", "themeum"),
				"param_name" => "btn_weight",
				"value" => array('Select'=>'','400'=>'400','100'=>'100','200'=>'200','300'=>'300','500'=>'500','600'=>'600','700'=>'700'),
				),				

			array(
				"type" => "textfield",
				"heading" => __("Button Font Letter Spacing Ex. 1px", "themeum"),
				"param_name" => "btn_spacing",
				"value" => "",
				),	

			array(
				"type" => "textfield",
				"heading" => __("Button Margin Ex. 5px 0 5px 0", "themeum"),
				"param_name" => "btn_margin",
				"value" => "",
				),							

			array(
				"type" => "dropdown",
				"heading" => __("Icon Position", "themeum"),
				"param_name" => "btn_icon_position",
				"value" => array('Select'=>'','None'=>'none','Before'=>'before','After'=>'after'),
				),																

			array(
				"type" => "dropdown",
				"heading" => __("Icon Name", "themeum"),
				"param_name" => "icon_name",
				"value" => getIconsList(),

				),	

			array(
				"type" => "textfield",
				"heading" => __("Custom Class ", "themeum"),
				"param_name" => "class",
				"value" => "",
				)
			),
		));
}