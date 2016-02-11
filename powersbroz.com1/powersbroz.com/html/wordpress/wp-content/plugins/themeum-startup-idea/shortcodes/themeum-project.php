<?php
add_shortcode( 'themeum_project', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'number_of_project' 	 => '3',
		'order_by'			     => 'ASC',
        'animation'              => 'fadeInLeft',
        'duration'              => '800',  
        'delay'             => '200',        
		), $atts));

	$output = '';

    $dur = '';
    $item_del = '';

    if ($duration) $dur .= (int) esc_attr($duration); 
    if ($delay) $item_del .= (int) esc_attr($delay); 
    

    $output .= '<div id="popular-ideas" class="popular-ideas carousel">';

    // The Query
    query_posts( array( 
                        'post_type'         => 'project',
                        'posts_per_page'    =>  esc_attr($number_of_project), 
                        'order'             => esc_attr($order_by)
                        ) );
    while ( have_posts() ) : the_post();

        $location = rwmb_meta("thm_location");
        $funding_goal = rwmb_meta("thm_funding_goal");
        $output .= '<div class="item wow '.esc_attr($animation).'" data-wow-duration="'.$dur.'ms" data-wow-delay="'.$item_del.'ms">';
            $output .= '<div class="image">';
                $output .= '<div class="fund-progress"><div class="bar" style="width:'.themeum_get_project_info(get_the_ID(),'percent').'%"></div></div>';
                 $output .= '<a href="'.get_the_permalink().'">';
                $output .= '<figure>';
                    if ( has_post_thumbnail() && ! post_password_required() ) { 
                    $output .=  get_the_post_thumbnail( get_the_ID(), 'project-thumb', array('class' => 'img-responsive'));
                    }else {
                        $output .= '<div class="no-image"></div>';
                    }
                    $output .= '<figcaption>';
                        $output .= '<p>'.themeum_get_project_info(get_the_ID(),'percent').'%</p>';
                        $output .= '<p class="pull-left">'.__("Rise Funded","themeum-startup-idea").'</p>';
                        $output .= themeum_get_ratting_data(get_the_ID());
                    $output .= '</figcaption>';
                $output .= '</figure>';
                $output .= '</a>';
            $output .= '</div>'; //image
        
            $output .= '<div class="clearfix"></div>';
        
            $output .= '<div class="details">';
                $output .= '<div class="country-name">'.esc_attr($location).'</div>';
                $output .= '<h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
                $output .= '<div class="entry-meta">';
                    $output .= '<span class="entry-food">'.get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ).'</span>';
                    $output .= '<span class="entry-money"><i class="fa fa-money"></i> '.__('total investment:','themeum-startup-idea').' <strong>'.themeum_get_currency_symbol().esc_attr($funding_goal).'</strong></span>';
                $output .= '</div>';
            $output .= '</div> '; //details
        $output .= '</div>'; //item
        $dur = $dur+200;
        $item_del = $item_del+100;
    endwhile;
    // Reset Query
    wp_reset_query();
    $output .= '</div>'; //popular-ideas
	return $output;
});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Project", "themeum-startup-idea"),
	"base" => "themeum_project",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Project", "themeum-startup-idea"),
	"category" => __('Themeum', "themeum-startup-idea"),
	"params" => array(

	array(
        "type" => "textfield",
        "heading" => __("Number Of Project","themeum-startup-idea"),
        "param_name" => "number_of_project",
        "description" => __("Enter the number of Project you want to display.", "themeum-startup-idea"),
        "value" => '', 
        ),

    array(
        "type" => "dropdown",
        "heading" => __("Order By:", "themeum-startup-idea"),
        "param_name" => "order_by",
        "value" => array('Select'=>'','Date Create (Ascending)'=>'ASC','Date Create (Descending)'=>'DESC'),
        ),
    array(
        "type" => "dropdown",
        "heading" => __("Animation", "themeum-startup-idea"),
        "param_name" => "animation",
        "value" => array('Select'=>'','No Style'=>'','Fade in Left'=>'fadeInLeft','Fade in Down'=>'fadeInDown','Fade in Right'=>'fadeInRight','Fade in Up'=>'fadeInUp'),
        ),  
    array(
        "type" => "textfield",
        "heading" => __("Animation Duration", "themeum-startup-idea"),
        "param_name" => "duration",
        "value" => "",
        ),  

    array(
        "type" => "textfield",
        "heading" => __("Animation Delay", "themeum-startup-idea"),
        "param_name" => "delay",
        "value" => "",
        ),    

		)
	));
}