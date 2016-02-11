<?php
add_shortcode( 'themeum_recent_blog', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'animation' 	        => '',
		'blog_post_number'		=> '',
        'blog_design'           => '6',
        'class'                 => ''
		), $atts));

	$output = $images = $animation = '';



    // The Query
    query_posts( array( 
                        'post_type'         => 'post',
                        'posts_per_page'    =>  esc_attr($blog_post_number), 
                        ) );
    // The Loop
    $i=0;
    while ( have_posts() ) : the_post();           
       
       if( $animation == "fade-left-right" ){
            if($i%2 == 0){ 
                $animation = 'fadeInLeft';
            }else{
                $animation = 'fadeInRight';
            }
       }
        $i++;

        if ( get_the_post_thumbnail( get_the_ID(), 'blog-thumb' ) != "" ) {
               $bg  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'blog-thumb' );
               $images = 'style = "background: url('.esc_url($bg[0]).') no-repeat; background-position: center top; background-size: auto 100%;"';
            }else{
               $images = 'style="background-color: #444;"';
            }

        $output .= '<div class="latest-posts  '.esc_attr($class).'">';
        $output .= '<div '.$images.' class="latest-posts-inner col-sm-'.esc_attr($blog_design).' ">';
            $output .= '<div class="wow '.esc_attr($animation).'" style="height: 400px;">';
                $output .= '<figure>';
                    $output .= '<figcaption>';
                        $output .= '<div class="entry-meta">';
                        $output .= '<span class="entry-date"><i class="fa fa-clock-o"></i>'.esc_attr(get_the_date('d M, Y')).'</span>';
                        $output .= '<span class="entry-author"><i class="fa fa-user"></i><a href="'. get_permalink().'">'.get_the_author().'</a></span>';
                        $output .= '</div>';
                        $output .= '<h3><a href="'. get_permalink().'">'.get_the_title().'</a></h3>';
                    $output .= '</figcaption>';
                $output .= '</figure>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

    endwhile;
    wp_reset_query();



	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Recent Blog", "themeum"),
	"base" => "themeum_recent_blog",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Title Heading", "themeum"),
	"category" => __('Themeum', "themeum"),
	"params" => array(

    array(
            "type" => "dropdown",
            "heading" => __("Animation", "themeum"),
            "param_name" => "animation",
            "value" => array('Select'=>'', 'No Animation'=>'', 'Fade Left/Right'=>'fade-left-right' ),
            ),  

	array(
        "type" => "textfield",
        "heading" => __("Number Of Blog Post","themeum"),
        "param_name" => "blog_post_number",
        "description" => __("Enter the number of Category you want to display.", "themeum"),
        "value" => 2, 
        ),

    array(
        "type" => "dropdown",
        "heading" => __("Blog Design", "themeum"),
        "param_name" => "blog_design",
        "value" => array( 'Select'=>'', 'Two Column'=>'6', 'Three Column'=>'4','Four Column'=>'3' ),
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