<?php
add_shortcode( 'themeum_category_projects', function($atts, $content = null) {

	extract(shortcode_atts(array(
        'project_cat'   => '',
		'order'		     => 'DESC',
		), $atts));

	$output = '';

    $output .= '<div id="popular-ideas">';

    // The Query
    query_posts( array( 
        'post_type'         => 'project', 
        'order'             => esc_attr($order),
        'tax_query' => array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => esc_attr($project_cat),
                ),
            ),
        )
    );


    // The Loop
    $inner = 1; $counter = 3;
    while ( have_posts() ) : the_post();

        $location = rwmb_meta("thm_location");
        $funding_goal = rwmb_meta("thm_funding_goal");

        // -----------------
        if( ($inner == 1)||($inner%3==1) ){ $output .= '<div class="row">'; }

        $output .= '<div class="col-sm-4">';
            $output .= '<div class="ideas-item">';
                $output .= '<div class="image">';
                    $output .= '<div class="fund-progress"><div class="bar" style="width:'.themeum_get_project_info(get_the_ID(),'percent').'%"></div></div>';
                    $output .= '<a href="'.get_the_permalink().'">';
                    $output .= '<figure>';
                    if ( has_post_thumbnail() && ! post_password_required() ){ 
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
                $output .= '</div>';
            
                $output .= '<div class="clearfix"></div>';
            
                $output .= '<div class="details">';
                    $output .= '<div class="country-name">'.esc_attr($location).'</div>';
                    $output .= '<h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
                    $output .= '<div class="entry-meta">';
                        $output .= '<span class="entry-food">'.get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ).'</span>';
                        $output .= '<span class="entry-money"><i class="fa fa-money"></i> '.__('investment:','themeum-startup-idea').' <strong>'.themeum_get_currency_symbol().esc_attr($funding_goal).'</strong></span>';
                    $output .= '</div>';
                $output .= '</div> ';
            $output .= '</div>'; //ideas item
        $output .= '</div>';

        // -----------------
        if( $inner%3 == 0 ){ $output .= '</div>'; }
        
        $inner++;
    endwhile;
    if( $inner%3 != 0 ){ $output .= '</div>'; }
    // Reset Query
    wp_reset_query();


    $output .= '</div>';

	return $output;

});


//Visual Composer

function themeum_project_cat_list()
{
    global $wpdb;
    $sql = "SELECT * FROM `".$wpdb->prefix."term_taxonomy` INNER JOIN `".$wpdb->prefix."terms` ON `".$wpdb->prefix."term_taxonomy`.`term_taxonomy_id`=`".$wpdb->prefix."terms`.`term_id` AND `".$wpdb->prefix."term_taxonomy`.`taxonomy`='project_category'";
    $terms = $wpdb->get_results( $sql );

    $cat_list = array();
    $cat_list['select'] = '';
    if(is_array($terms)){
        foreach ($terms as $term) {
            $cat_list[$term->slug] = $term->slug;
        }
    }
    return $cat_list;
}

if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Category Project List", "themeum-startup-idea"),
	"base" => "themeum_category_projects",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Project", "themeum-startup-idea"),
	"category" => __('Themeum', "themeum-startup-idea"),
	"params" => array(
    array(
        "type" => "dropdown",
        "heading" => __("Project Category:", "themeum-startup-idea"),
        "param_name" => "project_cat",
        "value" => themeum_project_cat_list(),
        ),
    array(
        "type" => "dropdown",
        "heading" => __("Order:", "themeum-startup-idea"),
        "param_name" => "order",
        "value" => array('Select' => '','none' => 'none','DESC' => 'DESC','ASC' => 'ASC'),
        ),

		)
	));
}