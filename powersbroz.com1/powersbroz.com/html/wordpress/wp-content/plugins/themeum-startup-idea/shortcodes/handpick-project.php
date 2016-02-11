<?php
add_shortcode( 'themeum_handpick_project', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'number_of_project' 	=> '',
        'class'                 => ''
		), $atts));

	$output = '';

    query_posts( array('p' => esc_attr($number_of_project), 'post_type' => 'project') );

    $output .= '<div id="handpick-project">';

    while ( have_posts() ) : the_post();
            $location = rwmb_meta("thm_location");
            $funding_goal = rwmb_meta("thm_funding_goal");

            $output .= '<div class="handpick '.esc_attr($class).'">';

                $output .= '<p class="rise-pund">'.__("Fund Raised","themeum-startup-idea").'</p>';
                $wave = (140/100)*themeum_get_project_info(get_the_ID(),'percent');
                $output .= '<div class="loading wave" style="background-size: 75px '.$wave.'px;">';
                    $output .= '<h2>'.themeum_get_project_info(get_the_ID(),'percent').'<span>%</span></h2>';
                $output .= '</div>';
                $output .= '<p class="location">'.esc_attr($location).'</p>';
                $output .= '<h3>'.get_the_title().'</h3>';
                $output .= '<div class="info">';
                    $output .= '<div class="hp-project-meta">';
                        $output .= '<span class="entry-money"><i class="fa fa-money"></i> '.__('Total Investment:','themeum-startup-idea').' <strong>'.themeum_get_currency_symbol().esc_attr($funding_goal).'</strong></span>';
                        $output .= '<span class="entry-rate">'.themeum_get_ratting_data(get_the_ID()).'</span>';
                    $output .= '</div>';
                     $output .= '<p class="intotext">'.themeum_the_excerpt_max_charlength(110).'</p>';
                     $output .= '<a class="btn btn-default" href="'.get_the_permalink().'">'.__('See Idea','themeum-startup-idea').'</a>';
                $output .= '</div>';
        
            $output .= '</div>'; //ideas item

    endwhile;
    // Reset Query
    wp_reset_query();
    $output .= '</div>';  
	return $output;

});
add_action( 'init', function(){

$projects = get_posts( array(
    'posts_per_page'   => -1,
    'offset'           => 0,
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_type'        => 'project',
    'post_status'      => 'publish',
    'suppress_filters' => true 
) );

$list_project = array();

foreach ($projects as $post) {
    $list_project[$post->ID] = $post->post_title;
}
$list_project = array_flip($list_project );


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Hand Pick Project", "themeum-startup-idea"),
	"base" => "themeum_handpick_project",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Project", "themeum-startup-idea"),
	"category" => __('Themeum', "themeum-startup-idea"),
	"params" => array(

	array(
        "type" => "dropdown",
        "heading" => __("Project Name","themeum-startup-idea"),
        "param_name" => "number_of_project",
        "description" => __("Eelect your Hand Pick Project", "themeum-startup-idea"),
        "value" => $list_project, 
        ),

    array(
        "type" => "textfield",
        "heading" => __("Custom Class ", "themeum-startup-idea"),
        "param_name" => "class",
        "value" => "",
        )    

		)
	));
}
});