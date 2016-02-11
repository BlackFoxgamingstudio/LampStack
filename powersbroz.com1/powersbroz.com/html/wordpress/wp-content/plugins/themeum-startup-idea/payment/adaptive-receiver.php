<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $parse_uri[0].'wp-load.php'; // Total WP Load Code.
require_once($wp_load);

use PayPal\IPN\PPIPNMessage;

class Configuration
{
    // For a full list of configuration parameters refer in wiki page (https://github.com/paypal/sdk-core-php/wiki/Configuring-the-SDK)
    public static function getConfig()
    {
        $config = array();
        $mode = get_option('paypal_mode');

        if( $mode == 'developer' ){
            $config = array( "mode" => "sandbox");
        }else{
            $config = array( "mode" => "live");
        }
        return $config;
    }
    
    // Creates a configuration array containing credentials and other required configuration parameters.
    public static function getAcctAndConfig()
    {
        $config = array(
                // Signature Credential
                "acct1.UserName"    => get_option('paypal_sandbox_api_user_name'),
                "acct1.Password"    => get_option('paypal_sandbox_api_password'),
                "acct1.Signature"   => get_option('paypal_sandbox_api_signature'),
                "acct1.AppId"       => get_option('paypal_sandbox_app_id',true)
                );
        
        return array_merge($config, self::getConfig());;
    }

}


if(file_exists( dirname(__FILE__). '/vendor2/autoload.php')) {
require 'vendor2/autoload.php';
}

$ipnMessage = new PPIPNMessage(null, Configuration::getConfig()); 


if($ipnMessage->validate()) {

    $raw_data = $ipnMessage->getRawData();
    $varai = maybe_unserialize( $raw_data );
    $atats = $varai['status'];

    if( $atats == "COMPLETED" ){


        $total_data = array();
        $data = $varai;
        foreach ($data as $key =>$value) {
            $total_data[urldecode($key)] = $value;
        }

        $pieces = explode(".", $total_data['transaction[0].invoiceId'] );
        $postid = $pieces[0];
        $donor_id = $pieces[1];

        $amount = trim( substr( trim($total_data['transaction[1].amount']) , 3) );

        $order_page = array(
            'post_title'    => get_the_title( $postid ),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'investment'
            );

        // Insert the post into the database
        $post_id = wp_insert_post( $order_page );
        add_post_meta( $post_id , 'themeum_project_name', get_the_title( $postid ) );
        add_post_meta( $post_id , 'themeum_invest_id', time().rand( 1000 , 9999 ));
        add_post_meta( $post_id , 'themeum_investor_user_id', esc_attr( $donor_id ));
        add_post_meta( $post_id , 'themeum_investment_project_id', esc_attr( $postid ));
        add_post_meta( $post_id , 'themeum_investment_amount', esc_attr( $amount ) );
        add_post_meta( $post_id , 'themeum_payment_id', esc_attr( $varai['tracking_id'] ));
        add_post_meta( $post_id , 'themeum_payment_method', 'paypal' );
        add_post_meta( $post_id , 'themeum_investment_date', date("Y m d h:i",time()) );
        add_post_meta( $post_id , 'themeum_status_all', 'complete' ); 

    }
} else {
    error_log("Error: Got invalid IPN data");   
}