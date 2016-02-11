<?php
defined('ABSPATH') or die("No script kiddies please!");

//View Order Table Start
function add_startup_idea_order_columns($columns) {
	$total_column = array_slice( $columns, 0, 1, true ) + array(
					'themeum_project_name'			 	=>__('Project Name','themeum-startup-idea'),
					'themeum_investment_project_id' 	=>__('Project ID','themeum-startup-idea'),
					'themeum_investor_user_id' 			=>__('Donor User ID','themeum-startup-idea'),
                    'themeum_investment_amount'			=>__('Amount','themeum-startup-idea'),
                    'themeum_payment_id' 				=>__('Payment ID','themeum-startup-idea'),
                    'themeum_status_all'				=>__('Status','themeum-startup-idea'),
                    ) + array_slice( $columns, 3, NULL, true );

    return $total_column;
}

add_filter('manage_investment_posts_columns' , 'add_startup_idea_order_columns');



// Set Value to Column
function custom_startup_idea_order_column( $column ) {
		global $post;
	    switch ( $column ) {
	      case 'themeum_investor_user_id':
	        echo esc_attr(get_post_meta( $post->ID , 'themeum_investor_user_id' , true ));
	        break;

	      case 'themeum_investment_project_id':
	        echo '<a href="'.get_edit_post_link( $post->ID ).'">'.esc_html(get_post_meta( $post->ID , 'themeum_investment_project_id' , true )).'</a>'; 
	        break;

	      case 'themeum_project_name':
	      	echo esc_html(get_post_meta( $post->ID , 'themeum_project_name' , true )); 
	      	break;

	      case 'themeum_investment_amount':
	      	$currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'Kč','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
			$symbol = '';
			$currency_type = get_option('paypal_curreny_code');
			if (array_key_exists( $currency_type , $currency_array)) {
			    $symbol = $currency_array[$currency_type];
			}else{
				 $symbol = '$';
			}
	        echo $symbol.''.esc_html(get_post_meta( $post->ID , 'themeum_investment_amount' , true )); 
	        break;

	      case 'themeum_status_all':
	      	echo esc_html(get_post_meta( $post->ID , 'themeum_status_all' , true ));
	      	break;

	      case 'themeum_payment_id':
	        echo esc_html(get_post_meta( $post->ID , 'themeum_payment_id' , true )); 
	        break;
	    }
	}
add_action( 'manage_investment_posts_custom_column' , 'custom_startup_idea_order_column' );

// Column Rearrange 
function startup_idea_sortable_columns( $columns ) {
	    $columns['themeum_investor_user_id'] 		= __('User ID','themeum-startup-idea');
	    $columns['themeum_investment_project_id'] 	= __('Project ID','themeum-startup-idea');
	    $columns['themeum_donate_amount'] 			= __('Price','themeum-startup-idea');
	    $columns['themeum_payment_id'] 				= __('Payment ID','themeum-startup-idea');
	    $columns['themeum_status_all'] 				= __('themeum_status_all','themeum-startup-idea');
	    return $columns;
	}
add_filter( 'manage_edit-investment_sortable_columns', 'startup_idea_sortable_columns' );






