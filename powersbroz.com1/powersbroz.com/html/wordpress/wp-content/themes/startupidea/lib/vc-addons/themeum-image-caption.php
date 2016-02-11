<?php
add_shortcode( 'themeum_image_caption', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'image'				=> '',
		'title'				=> '',
		'size'				=> '12',
		'margin'			=> '2px 0',
		'color'				=> '#666666',
		'class'				=> '',
		), $atts));

	$src_image   = wp_get_attachment_image_src($image, 'blog-full');

	$style ='';

	if($size) $style .= 'font-size:' . (int) esc_attr($size) . 'px;line-height:' . (int) esc_attr($size) . 'px;';

	if($margin) $style .= 'margin:' . esc_attr($margin) .';';

	if($color) $style .= 'color:' . esc_attr($color);

	
	$output = '';

	$output .= '<div class="themeum-image-caption '.esc_attr($class).'">';
	$output .= '<figure>';
	if($src_image) {
	 $output .= '<img class="img-responsive" src="'.esc_url($src_image[0]).'" alt="image">';
	}
	if($title) {
		$output .= '<figcaption><p class="themeum-caption-title" style="'.$style.'">'.esc_attr($title).'</p></figcaption>';
	}
	$output .= '</figure>';
	$output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Image Caption", "themeum"),
	"base" => "themeum_image_caption",
	'icon' => 'icon-thm-image-caption',
	"class" => "",
	"description" => __("Widget Image Caption", "themeum"),
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
			"heading" => __("Caption Title", "themeum"),
			"param_name" => "title",
			"value" => ""
			),	

		array(
			"type" => "textfield",
			"heading" => __("Caption Title Font Size", "themeum"),
			"param_name" => "size",
			"value" => ""
			),	

		array(
			"type" => "colorpicker",
			"heading" => __("Caption Title Color", "themeum"),
			"param_name" => "color",
			"value" => "",
			),						

		array(
			"type" => "textfield",
			"heading" => __("Caption Title Margin  Ex. 2px 2px 2px 2px", "themeum"),
			"param_name" => "margin",
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