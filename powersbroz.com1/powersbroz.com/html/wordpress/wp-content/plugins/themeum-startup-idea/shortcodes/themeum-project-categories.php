<?php
add_shortcode( 'themeum_project_categories', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'order_by'			 => 'name',
        'order'              => 'ASC',
        'count'              => 'yes',
		), $atts));

    $args = array(
        'orderby'   => $order_by,
        'order'     => $order,
    );

    $terms = get_terms('project_category', $args);

    $output = '';

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) )
    {
        $output .= '<div class="project-category-list clearfix">';

        $output .= '<ul>';

        foreach ( $terms as $term )
        {
            $term_link = get_term_link( $term );

            // If there was an error, continue to the next term.
            if ( is_wp_error( $term_link ) ) {
                continue;
            }

            $output .= '<li><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';
        }

        $output .= '</ul>';
        $output .= '</div>';
    }
	
	return $output;
});


//Visual Composer

if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Project Categories", "themeum-startup-idea"),
	"base" => "themeum_project_categories",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Startup Project Category list", "themeum-startup-idea"),
	"category" => __('Themeum', "themeum-startup-idea"),
	"params" => array(

    array(
        "type" => "dropdown",
        "heading" => __("Order By:", "themeum-startup-idea"),
        "param_name" => "order_by",
        "value" => array('Select'=>'','id'=>'ID','count'=>'Count','count'=>'Count','name'=>'Name','slug'=>'Slug','none'=>'None'),
        ),
    array(
        "type" => "dropdown",
        "heading" => __("Order:", "themeum-startup-idea"),
        "param_name" => "order",
        "value" => array('Select'=>'','ASC'=>'ASC','DESC'=>'DESC'),
        ),
    array(
        "type" => "dropdown",
        "heading" => __("Show Count:", "themeum-startup-idea"),
        "param_name" => "count",
        "value" => array('Select'=>'','true'=>'yes','false'=>'No'),
        ),
		)
	));
}