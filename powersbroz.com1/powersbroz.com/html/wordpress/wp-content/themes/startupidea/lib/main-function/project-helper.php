<?php 

function themeum_get_project_info($post_id,$type){

    // themeum_get_project_info(get_the_ID(),'budget');
    // themeum_get_project_info(get_the_ID(),'percent');
    // themeum_get_project_info(get_the_ID(),'collected');
    // themeum_get_project_info(get_the_ID(),'investor_number');

    if( $type=='budget' ){          // Return the total Budget
        $budget = esc_attr(get_post_meta($post_id,"thm_funding_goal",true));  // Get the total Budget
        return $budget;
    }elseif( $type == 'percent' ){  // Return the Funding Percentage
        $budget = esc_attr(get_post_meta($post_id,"thm_funding_goal",true));
        global $wpdb;
        $result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total FROM ".$wpdb->prefix."postmeta WHERE post_id = ANY(SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'themeum_investment_project_id', $post_id, 'themeum_investment_amount'));
        $funding = 0;
          if($budget != "" && $budget != 0 ){
            if(isset($result->total)){
              if( $result->total != 0 && is_numeric($result->total)){
                $funding =  floor((($result->total)/$budget)*100);
              }
            }
          }
          return $funding;
    }elseif( $type == 'collected' ){ // return Total collected fund
        global $wpdb;
        $result = $wpdb->get_row( $wpdb->prepare("SELECT SUM(meta_value) as total FROM ".$wpdb->prefix."postmeta WHERE post_id = ANY(SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'themeum_investment_project_id', $post_id, 'themeum_investment_amount'));
        return $result->total;
    }else{ // Return Investor Number
        $args = array(
            'post_type' => 'investment',
            'meta_key' => 'themeum_investment_project_id',
            'meta_value' => $post_id,
            'meta_compare' => '=',
            'orderby' => 'meta_value',
            'order' => 'ASC'
          );
        $events = new WP_Query($args);
        $investor_num = count($events->posts); // Total Number of investor
        return $investor_num;
    }

}


/*--------------------------------------------------------------
 *      Project Get Currency Symbol & Code (Add New Project)
 *-------------------------------------------------------------*/
function themeum_get_currency_symbol(){
    $currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'Kč','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
    $symbol = '';
    $currency_type = esc_attr(get_option('paypal_curreny_code'));
    if (array_key_exists( $currency_type , $currency_array)) {
        $symbol = $currency_array[$currency_type];
    }else{
         $symbol = '$';
    }
    return $symbol;
}
function themeum_get_currency_code(){
    $code = '';
    $currency_type = esc_attr(get_option('paypal_curreny_code'));
    if($currency_type == ''){
        $code = 'USD';
    }else{
        $code = $currency_type;
    }
    return $code;
}




/*--------------------------------------------------------------
 *      Project Get Rat option (Add New Project)
 *-------------------------------------------------------------*/
function themeum_get_ratting_data($post_id){

    $output_arr =  $html = '';
    $output = get_post_meta($post_id,'project_ratting');
    $i = 0;
    if(is_array($output)){
        foreach ($output as $value) {
            $var = explode('*###*',$value);
            $output_arr[] = $var[0];
            $i = $i + $var[0];
        }
        $i = ceil($i/count($output_arr));
    }
    
    $html = '<ul class="list-unstyled list-inline">';
    for ($j=1; $j <=5 ; $j++) { 
        if($j<=$i){
            $html .= '<li><i class="fa fa-star"></i></li>';
        }else{
            $html .= '<li><i class="fa fa-star-o"></i></li>';
        }
    }
    $html .= '</ul>';

return $html; 
}