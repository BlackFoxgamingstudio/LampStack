<?php 

function themeum_user_project_id_list($id){
    global $wpdb;
    $results_id = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE post_id = ANY(SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '%s'  AND meta_value = '%d') AND meta_key = '%s'", 'themeum_investor_user_id', $id, 'themeum_investment_project_id'));
    $id_list = '';
    if(isset($results_id)){
        foreach ($results_id as $value) {
           $id_list[] = $value->meta_value;
        }
    }if(!is_array($id_list)){ $id_list[] = 123343434; }
    return $id_list;
}