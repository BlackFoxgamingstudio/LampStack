<?php
add_shortcode( 'themeum_person', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'person_img' => '',
		'animation'			=> 'fadeInLeft',
		'duration' 			=> '800',  
        'delay' 			=> '200',
		'person_name' => '',
		'person_deg'	=> '',
		'facebook_url' => '',
		'twitter_url' => '',
		'gplus_url' => '',
		'linkedin_url' => '',
		'pinterest_url' => '',
		'delicious_url' => '',
		'tumblr_url' => '',
		'stumbleupon_url' => '',
		'dribble_url' => '',
		'btn_text' => '',
		'btn_url' => '',
		'class' => ''
		), $atts));

		$dur = '';
		$item_del = '';

		if ($duration) $dur .= (int) esc_attr($duration) .'ms'; 
	 	if ($delay) $item_del .= (int) esc_attr($delay) .'ms'; 

		$output  	 = '<div class="themeum-person '.esc_attr($class).'">';
		$output  	 .= '<div class="wow '.esc_attr($animation).' text-center" data-wow-duration="'.esc_attr($dur).'" data-wow-delay="'.esc_attr($item_del).'">';
		$output  	.= '<figure class="themeum-person-details">';
		$src_image   = wp_get_attachment_image_src($person_img, 'full');
		$output  	.= '<img src="' . esc_url($src_image[0]). '" class="img-responsive" alt="photo">';
		$output  	.= '<figcaption class="themeum-person-caption">';
		if($btn_url){
		$output  	.= '<p><a href="' . esc_url($btn_url) . '" class="btn btn-primary">' . esc_attr($btn_text) . '</a></p>';
		}
		$output  	.= '<div class="social-icon">';
		$output  	.= '<ul class="list-unstyled list-inline">';
		if(isset($facebook_url) && !empty($facebook_url)) {
		$output 	.= '<li><a class="facebook" href="' . esc_url($facebook_url) . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
		}

		if(isset($twitter_url) && !empty($twitter_url)) {
		$output 	.= '<li><a class="twitter" href="' . esc_url($twitter_url) . '" target="_blank" ><i class="fa fa-twitter"></i></a></li>';
		}	

		if(isset($gplus_url) && !empty($gplus_url)) {
		$output 	.= '<li><a class="g-plus" href="' . esc_url($gplus_url) . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
		}	

		if(isset($linkedin_url) && !empty($linkedin_url)) {
		$output 	.= '<li><a class="linkedin" href="' . esc_url($linkedin_url) . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
		}

		if(isset($pinterest_url) && !empty($pinterest_url)) {
		$output 	.= '<li><a class="pinterest" href="' . esc_url($pinterest_url) . '" target="_blank"><i class="fa fa-pinterest"></i></a></li>';
		}	

		if(isset($delicious_url) && !empty($delicious_url)) {
		$output 	.= '<li><a class="delicious" href="' . esc_url($delicious_url) . '" target="_blank"><i class="fa fa-delicious"></i></a></li>';
		}	

		if(isset($tumblr_url) && !empty($tumblr_url)) {
		$output 	.= '<li><a class="tumblr" href="' . esc_url($tumblr_url) . '" target="_blank"><i class="fa fa-tumblr"></i></a></li>';
		}	

		if(isset($stumbleupon_url) && !empty($stumbleupon_url)) {
		$output 	.= '<li><a class="stumbleupon" href="' . esc_url($stumbleupon_url) . '" target="_blank"><i class="fa fa-stumbleupon"></i></a></li>';
		}	

		if(isset($dribble_url) && !empty($dribble_url)) {
		$output 	.= '<li><a class="dribble" href="' . esc_url($dribble_url) . '" target="_blank"><i class="fa fa-dribbble"></i></a></li>';
		}
		$output  	.= '</ul>';
		$output  	.= '</div>';
		$output  	.= '</figcaption>';
		$output  	.= '</figure>';
		$output  	.= '<div class="info">';
		if ($person_name) $output 	.= '<h3 class="person-title">' . esc_attr($person_name) . '</h3>';
		if ($person_deg) $output 	.= '<h4 class="person-deg">' . esc_attr($person_deg) . '</h4>';
		$output  	.= '</div>';
		$output  	 .= '</div>';
		$output  	 .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => __("Person", "themeum"),
		"base" => "themeum_person",
		'icon' => 'icon-thm-person',
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
				"type" => "attach_image",
				"heading" => __("Upload Person Image", "themeum"),
				"param_name" => "person_img",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Person Name", "themeum"),
				"param_name" => "person_name",
				"value" => "",
				"admin_label"=>true,
				),

			array(
				"type" => "textfield",
				"heading" => __("Team Designation", "themeum"),
				"param_name" => "person_deg",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Facebook URL", "themeum"),
				"param_name" => "facebook_url",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Twitter URL", "themeum"),
				"param_name" => "twitter_url",
				"value" => "",
				"admin_label"=>true,
				),

			array(
				"type" => "textfield",
				"heading" => __("Google Plus URL", "themeum"),
				"param_name" => "gplus_url",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Linkedin URL", "themeum"),
				"param_name" => "linkedin_url",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Pinterest URL", "themeum"),
				"param_name" => "pinterest_url",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Delicious URL", "themeum"),
				"param_name" => "delicious_url",
				"value" => "",
				"admin_label"=>true,
				),

			array(
				"type" => "textfield",
				"heading" => __("Tumblr URL", "themeum"),
				"param_name" => "tumblr_url",
				"value" => "",
				"admin_label"=>true,
				),			

			array(
				"type" => "textfield",
				"heading" => __("Stumbleupon URL", "themeum"),
				"param_name" => "stumbleupon_url",
				"value" => "",
				"admin_label"=>true,
				),

			array(
				"type" => "textfield",
				"heading" => __("Dribble URL", "themeum"),
				"param_name" => "dribble_url",
				"value" => "",
				"admin_label"=>true,
				),

			array(
				"type" => "textfield",
				"heading" => __("Button Text", "themeum"),
				"param_name" => "btn_text",
				"value" => "Button"
				),


			array(
				"type" => "textfield",
				"heading" => __("Button Url", "themeum"),
				"param_name" => "btn_url",
				"value" => ""
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