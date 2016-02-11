<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $parse_uri[0].'wp-load.php'; // Total WP Load Code.
require_once($wp_load);

require_once plugin_dir_path( __FILE__ ).'vendor/autoload.php';

\Stripe\Stripe::setApiKey("sk_test_lC6IlLsQ7Mp7CIS1QR6yM4Nn");

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

$event_id = $event_json->id;

$event = \Stripe\Event::retrieve($event_id);
$event_object = $event->data->object;

if ($event->type == 'charge.succeeded')
{
    $event_object = $event->data->object;

    $amount = sprintf('%0.2f', $event_object->amount / 100.0); // amount
    $balance_transaction = $event_object->balance_transaction;
    $carged_id = $event_object->id;

    $event_metadata = $event_object->metadata;

    if(isset($event_metadata->item_number)){

        $order_page = array(
            'post_title'    => $event_metadata->item_name,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'investment'
        );

        $post_id = wp_insert_post( $order_page );
        add_post_meta( $post_id , 'themeum_project_name', esc_attr( $event_metadata->item_name ));
        add_post_meta( $post_id , 'themeum_invest_id', esc_attr( $carged_id ));
        add_post_meta( $post_id , 'themeum_investor_user_id', esc_attr( $event_metadata->user_id ));
        add_post_meta( $post_id , 'themeum_investment_project_id', esc_attr( $event_metadata->item_number ));
        add_post_meta( $post_id , 'themeum_investment_amount', esc_attr( $amount ));
        add_post_meta( $post_id , 'themeum_payment_id', esc_attr( $balance_transaction ));
        add_post_meta( $post_id , 'themeum_payment_method', 'stripe' );
        add_post_meta( $post_id , 'themeum_investment_date', date("Y m d h:i",time()) );
        add_post_meta( $post_id , 'themeum_status_all', 'complete' );              

    }
}