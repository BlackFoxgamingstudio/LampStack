<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $parse_uri[0].'wp-load.php'; // Total WP Load Code.
require_once($wp_load);

    // Check if paypal request or response
if (isset($_POST["txn_id"]) && isset($_POST["txn_type"])){

    // Response from Paypal
    header('HTTP/1.1 200 OK');

    //read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }


    //SplmsHelper::testIpn($order_item_name, $item_number);
    //post back to PayPal system to validate (replaces old headers)
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Host: www.paypal.com\r\n";
    $header .= "Connection: close\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    // Open a socket for the acknowledgement request
    if ( 'developer' == get_option('paypal_mode') ) {
        $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
    }elseif( 'real' == get_option('paypal_mode') ){
        $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
    }


    // Send the HTTP POST request back to PayPal for validation
    fputs($fp, $header . $req);

    //error connecting to paypal
    //if (!$fp) {  }

        //successful connection    
    if ($fp) {
        fputs ($fp, $header . $req);

        while (!feof($fp)) {
            $res = fgets ($fp, 1024);

            $res = trim($res);              
            if (strcmp($res, "VERIFIED") == 0) {               


                $var_tnx = $_POST['txn_id'];
                global $wpdb;
                $user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key='themeum_payment_id' AND meta_value='$var_tnx' " );
                
                if( $user_count == 0 ){
                    // Insert Into Database Start   

                    if(isset($_POST['item_number'])){

                        $order_page = array(
                            'post_title'    => $_POST['item_name'],
                            'post_content'  => '',
                            'post_status'   => 'publish',
                            'post_author'   => 1,
                            'post_type'     => 'investment'
                            );

                        // Insert the post into the database
                        $post_id = wp_insert_post( $order_page );
                        add_post_meta( $post_id , 'themeum_project_name', esc_attr($_POST['item_name']));
                        add_post_meta( $post_id , 'themeum_invest_id', time().rand( 1000 , 9999 ));
                        add_post_meta( $post_id , 'themeum_investor_user_id', esc_attr($_POST['custom']));
                        add_post_meta( $post_id , 'themeum_investment_project_id', esc_attr($_POST['item_number']));
                        add_post_meta( $post_id , 'themeum_investment_amount', esc_attr($_POST['mc_gross']) );
                        add_post_meta( $post_id , 'themeum_payment_id', esc_attr($_POST['txn_id'] ));
                        add_post_meta( $post_id , 'themeum_payment_method', 'paypal' );
                        add_post_meta( $post_id , 'themeum_investment_date', date("Y m d h:i",time()) );
                        add_post_meta( $post_id , 'themeum_status_all', 'complete' );              

                    }
                }
                
            }
            if (strcmp ($res, "INVALID") == 0) {
            //Invalid Request
            }
} // endWhile
fclose($fp);
}
}