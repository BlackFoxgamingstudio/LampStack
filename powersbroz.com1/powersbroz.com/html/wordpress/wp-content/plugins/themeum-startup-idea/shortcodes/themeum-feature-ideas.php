<?php
add_shortcode( 'themeum_feature_ideas', function($atts, $content = null) {

	extract(shortcode_atts(array(
        'category_number'   => '4',
		'order_by'			=> 'ASC'
		), $atts));

	$output = '';
	$category = array();

	$reasult = get_terms('project_category',array('order' => esc_attr($order_by )));

	$output .= '<div id="featured-ideas" class="tabpanel">';


        $output .= '<div class="navigation nav nav-tabs">';
            $output .= '<ul class="list-unstyled list-inline text-center">';
            $i = 1;
                foreach ( $reasult as $value ) {
                    if($i <= esc_attr($category_number)) {
                    		$category[] = $value->slug;
                        	if( $i == 1 ){
                        		$output .= '<li class="active"><a href="#'.$value->slug.'" data-toggle="tab">'.$value->name.'</a></li>';
                        	}else{
                        		$output .= '<li><a href="#'.$value->slug.'" data-toggle="tab">'.$value->name.'</a></li>';	
                        	}
                        }
                    	$i++;
                    }
            $output .= '</ul>';
        $output .= '</div>';

        $output .= '<div class="tab-content">';
        	$i=1;
        	foreach ($category as $value) {

            		if( $i == 1){
            			$output .= '<div class="details tab-pane active fade in" id="'.$value.'">';
            		}else{
            			$output .= '<div class="details tab-pane fade" id="'.$value.'">';
            		}
        			$i++;
		

            		// The Query
					query_posts( array( 
										'post_type' => 'project', 
										'project_category' => $value , 
										'posts_per_page' => 1 , 
										'meta_query' => array(
											'relation' => 'AND',
														array(
															'key' => 'thm_featured',
															'value'=> 1,
															'compare' => '='
															)) 
										) );
					// The Loop
					while ( have_posts() ) : the_post();
						
						$location = rwmb_meta("thm_location");
						$project_type = rwmb_meta("thm_type");
						$funding_goal = rwmb_meta("thm_funding_goal");

                         $output .= '<div class="item media">';
                             $output .= '<div class="pull-left">';
                             $output .= '<div class="image">';
                                 $output .= '<a href="'.get_the_permalink().'"><figure class="featured-image">';
                                 $output .= '<div class="fund-progress"><div class="bar" style="width:'.themeum_get_project_info(get_the_ID(),'percent').'%"></div></div>';
                                    if ( has_post_thumbnail() && ! post_password_required() ) { 
                                    $output .= get_the_post_thumbnail( get_the_ID(), 'featured-ideas', array('class' => 'img-responsive'));
                                    }else {
                                        $output .= '<div class="no-image"></div>';
                                    }
                                     $output .= '<figcaption>';
                                         $output .= '<p>'.themeum_get_project_info(get_the_ID(),'percent').'%</p>';
                                         $output .= '<p class="pull-left">'.__('Rise Funded','themeum-startup-idea').'</p>';
                                         $output .= themeum_get_ratting_data(get_the_ID());
                                     $output .= '</figcaption>';
                                 $output .= '</figure></a>';
                                $output .= '</div>';
                             $output .= '</div>';
                             $output .= '<div class="media-body" >';
                                 $output .= '<p class="team-name">'.esc_attr($location).'</p>';
                                 $output .= '<h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
                                 $output .= '<div class="entry-meta">';
                                     $output .= '<span class="entry-tag"> '.get_the_term_list( get_the_ID(), 'project_tag', '<i class="fa fa-tags"></i> ', ', ' ).'</span>';
                                     if($project_type){
                                         	$output .= '<span class="entry-category"><i class="fa fa-trophy"></i> '.esc_attr($project_type).'</span>';	
                                         }
                                     $output .= '<span class="entry-comments"><i class="fa fa-money"></i> '.__('Total Investment','themeum-startup-idea').': <strong>'.themeum_get_currency_symbol().esc_attr($funding_goal).'</strong></span>';
                                 $output .= '</div>';
                                 $output .= '<p>';
                                     $output .= themeum_the_excerpt_max_charlength(200);
                                $output .= '</p>';
                                $output .= '<a href="'.get_the_permalink().'" class="btn btn-default">'.__('See Ideas','themeum-startup-idea').'</a>';
                                 
                             $output .= '</div>';
                         $output .= '</div>';
                     $output .= '</div>';

                endwhile;
				// Reset Query
				wp_reset_query();

        	}

        $output .= '</div>';
    $output .= '</div>';

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => __("Feature Ideas", "themeum-startup-idea"),
	"base" => "themeum_feature_ideas",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => __("Widget Title Heading", "themeum-startup-idea"),
	"category" => __('Themeum', "themeum-startup-idea"),
	"params" => array(

	array(
        "type" => "textfield",
        "heading" => __("Number Of Category","themeum-startup-idea"),
        "param_name" => "category_number",
        "description" => __("Enter the number of Category you want to display.", "themeum-startup-idea"),
        "value" => '', 
        ),

    array(
        "type" => "dropdown",
        "heading" => __("Order By:", "themeum-startup-idea"),
        "param_name" => "order_by",
        "value" => array('Select'=>'','Date Create (Ascending)'=>'ASC','Date Create (Descending)'=>'DESC'),
        ),         	

		)
	));
}