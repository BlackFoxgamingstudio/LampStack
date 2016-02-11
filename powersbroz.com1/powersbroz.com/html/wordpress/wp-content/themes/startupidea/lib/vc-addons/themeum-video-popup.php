<?php
add_shortcode( 'themeum_video_popup', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'video_url' 	        => '',
        'style'                 => '',
        'title'                 => '',
        'short_info'            => '',
        'btn_text1'             => '',
        'btn_text2'             => '',
        'btn_url1'              => '',
        'btn_url2'              => '',
		'content_background'	=> '#EDEDED',
        'image'                 => ''
		), $atts));

	$output = $image_style = $bg = '';

    $video = parse_url($video_url);

    if($content_background) $bg .= 'style="background-color: '.esc_attr($content_background).';"'; 

    $src_image   = wp_get_attachment_image_src($image, 'full');

        if ( $src_image[0] != "" ) {
               $image_style = 'style = "background: url('.esc_url($src_image[0]).') no-repeat;width:50%;"';
            }else{
               $image_style = 'style="background-color: #444;width:50%;"';
            }

    $output .= '<div class="video-section">';


        if($style == ''){
            $video_id = themeum_get_video_id($video_url);
            $output .= '<div class="popup-video-stype" style="width:50%">';
                $output .= '<div class="external-video"><iframe class="video" width="50%" src="'.esc_url($video_id).'" frameborder="0" allowfullscreen></iframe></div>';
            $output .= '</div>';
        }else{
            if( $video['host']=="youtu.be" || $video['host']=="www.youtube.com" || $video['host']=="youtube.com"){
                $output .= '<div class="popup-video-stype" '.$image_style.'>';
                    $output .= '<div class="youtube video-icon"><a href="'.esc_url($video_url).'" data-rel="prettyPhoto">';
                        $output .= '<span class="video-caption"><i class="fa fa-play-circle-o"></i></span></a>';
                    $output .= '</div>';
                $output .= '</div>';
            }else{
                $output .= '<div class="popup-video-stype" '.$image_style.'>';
                    $output .= '<div class="vimeo video-icon" '.$image_style.'><a  href="'.esc_url($video_url).'" data-rel="prettyPhoto">';
                        $output .= '<span class="video-caption"><i class="fa fa-play-circle-o"></i></span></a>';
                    $output .= '</div>';
                $output .= '</div>';
            }
        }

        $output .= '<div class="container">';
            $output .= '<div class="row">';
                $output .= '<div class="col-sm-6 col-sm-offset-6">';
                    $output .= '<div class="video-popup-inner" '.$bg.'>';
                    if ($title){ $output .= '<h3 class="video-popup-title">' . esc_attr($title) . '</h3>'; }
    
                    if ($short_info){ $output .= '<div class="video-popup-content"><p>' . balanceTags($short_info) . '</div>'; }
                    if ($btn_url1) { $output .= '<a class="video-popup-btn1" href="' . esc_url($btn_url1) . '">' . esc_attr($btn_text1) . '</a>'; }
                    if ($btn_url2) { $output .= '<a class="video-popup-btn2" href="' . esc_url($btn_url2) . '">' . esc_attr($btn_text2) . '</a>'; }
                    $output .= '</div>';
                $output .= '</div>'; //col-s-6
            $output .= '</div>'; //row
        $output .= '</div>';  //container   



    $output .= '</div>'; //video setion

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Video Popup", "themeum"),
	"base" => "themeum_video_popup",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Video Popup", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

	array(
        "type" => "textfield",
        "heading" => __("Video URL:","themeum"),
        "param_name" => "video_url",
        "description" => __("Youtube/Vimo video URL", "themeum"),
        "value" => "", 
        ),

    array(
        "type" => "dropdown",
        "heading" => __("Style:", "themeum"),
        "param_name" => "style",
        "value" => array('Select'=>'','No Style'=>'','Popup Style'=>'popup'),
        ),	

    array(
        "type" => "attach_image",
        "heading" => __("Insert Image BG image(Only for No Style design.)", "themeum"),
        "param_name" => "image",
        "value" => "",
        ),  

    array(
        "type" => "textfield",
        "heading" => __("Title", "themeum"),
        "param_name" => "title",
        "value" => "",
        ),    

    array(
        "type" => "textarea_html",
        "heading" => __("Short Information", "themeum"),
        "param_name" => "short_info",
        "value" => "",
        ),

    array(
        "type" => "colorpicker",
        "heading" => __("Content Background", "themeum"),
        "param_name" => "content_background",
        "value" => "",
        ),   

    array(
        "type" => "textfield",
        "heading" => __("Button Text 1", "themeum"),
        "param_name" => "btn_text1",
        "value" => ""
        ),    

    array(
        "type" => "textfield",
        "heading" => __("Button Url 1", "themeum"),
        "param_name" => "btn_url1",
        "value" => ""
        ),    

    array(
        "type" => "textfield",
        "heading" => __("Button Text 2", "themeum"),
        "param_name" => "btn_text2",
        "value" => ""
        ),    

    array(
        "type" => "textfield",
        "heading" => __("Button Url 2", "themeum"),
        "param_name" => "btn_url2",
        "value" => ""
        ),

		)
	));
}