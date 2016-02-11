<?php
defined('ABSPATH') or die("No script kiddies please!");

require_once plugin_dir_path( __FILE__ ).'vendor/autoload.php';

add_action('init','paypal_payment');
function paypal_payment(){
  if (!is_admin()){
    wp_enqueue_script('jquery'); //jQuery add if it not exits.
  }
}


// Buy Now Button
function insert_post_buynow( $id = 0 )
{

  $id = $id;

  $html_address     = '';
  $continue_button  = $continue_button_logout = '';

  $enable_paypal = get_option('enable_paypal_payment');
  $enable_stripe = get_option('enable_stripe_payment');

  if ( $enable_paypal && !$enable_stripe ) {
    $gateway_type = 'paypal';
  }elseif ( !$enable_paypal && $enable_stripe) {
    $gateway_type = 'stripe';
  }elseif ( $enable_paypal && $enable_stripe ) {
    $gateway_type = esc_attr(get_option('default_payment'));
  }else{
    $gateway_type = '';
    
  }

  if ( is_user_logged_in() )
  {
    $continue_button = '<div class="col-md-12 alert alert-danger" role="alert"><strong>'.__('Warning!','themeum-startup-idea').'</strong><br />'.__('Please contact with site owner. No payment system is active.','themeum-startup-idea').'</div>';

    if ($gateway_type) {
      $continue_button = '<input type="submit" class="donate-now" id="submitbtn" value="'.__("Funding","themeum-startup-idea").' ">';
    }
  }
  else
  {
    $continue_button_logout = '<h4><div class="col-md-12 text-center pull-right alert alert-danger" role="alert">'.__('Login First To Checkout.','themeum-startup-idea').'</div></h4>';
  }

  $user_id    = get_current_user_id();
  $first_name   = get_user_meta( $user_id, 'startup_first_name', true);
  $last_name    = get_user_meta( $user_id, 'startup_last_name', true);
  $email      = get_user_meta( $user_id, 'startup_email', true);
  $address1     = get_user_meta( $user_id, 'startup_address1', true);
  $address2     = get_user_meta( $user_id, 'startup_address2', true);
  $city       = get_user_meta( $user_id, 'startup_city', true);
  $state      = get_user_meta( $user_id, 'startup_state', true);
  $zip      = get_user_meta( $user_id, 'startup_zip', true);
  $country    = get_user_meta( $user_id, 'startup_country', true);

  $html_address .= '<form id="fund_paymet_form" action="" method="post" >';

  $html_address .= '<input id="item_name" name="item_name" type="hidden" value="'. esc_html(get_the_title($id)) .'" />';
  $html_address .= '<input id="item_number" name="item_number" type="hidden" value="'. esc_html($id) .'" />';

  $html_address .= '<div class="row">';

  
  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="first_name">First Name</label>';
  $html_address .= '<input class="form-control" id="first_name" name="first_name" type="text" value="'. $first_name .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="last_name">Last Name</label>';
  $html_address .= '<input class="form-control" id="last_name" name="last_name" type="text" value="'.$last_name.'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-12">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="email">Email:</label>';
  $html_address .= '<input class="form-control" id="email" name="email" type="text" value="'. $email .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-12">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="address1">Address 1:</label>';
  $html_address .= '<input class="form-control" id="address1" name="address1" type="text" value="'. $address1 .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-12">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="address2">Address 2:</label>';
  $html_address .= '<input class="form-control" id="address2" name="address2" type="text" value="'. $address2 .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="city">City:</label>';
  $html_address .= '<input class="form-control" id="city" name="city" type="text" value="'. $city .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="state">State:</label>';
  $html_address .= '<input class="form-control" id="state" name="state" type="text" value="'. $state .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="zip">Zip:</label>';
  $html_address .= '<input class="form-control" id="zip" name="zip" type="text" value="'. $zip .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-6">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="country">Country:</label>';
  $html_address .= '<input class="form-control" id="country" name="country" type="text" value="'. $country .'" required />';
  $html_address .= '</div>';
  $html_address .= '</div>';

  if ($gateway_type)
  {
    $html_address .= '<div class="col-md-12">';
    $html_address .= '<div class="form-group">';
    $html_address .= '<label for="country">Select Paypal/Card:</label>';

    if ($enable_paypal) {
      $html_address .= '<p><input name="gateway_type" type="radio" value="paypal" '. (($gateway_type == 'paypal')? 'checked':"") .' /><span>Pay with Paypal</span><img src="'. plugins_url( 'assets/images/paypal.png', dirname(__FILE__) ) .'" alt="paypal payment" /></p>';
    }

    if ($enable_stripe) {
      $html_address .= '<p><input name="gateway_type" type="radio" value="card" '. (($gateway_type == 'stripe')? 'checked':"") .' /><span>Pay with Credit/Debit Card</span><img src="'. plugins_url( 'assets/images/creadit-card.png', dirname(__FILE__) ) .'" alt="paypal payment" /></p>';
    }

    $html_address .= '</div>';
    $html_address .= '</div>';
  }


  $html_address .= '<input type="hidden" id="card_token" name="card_token">';
    $html_address .= '<input type="hidden" id="stripeEmail" name="stripeEmail">';

    $html_address .= '<div class="col-md-12">';
  $html_address .= '<div class="form-group">';
  $html_address .= '<label for="amount">Amount in '.themeum_get_currency_symbol().'</label>';
  $reward_amount = '';
  if(isset($_GET['reward'])){ 
    $reward_amount = $_GET['reward']; 
  }
  $html_address .= '<input class="form-control" type="text" name="amount" value="'.$reward_amount.'" required id="amount" >';
  $html_address .= '</div>';
  $html_address .= '</div>';

  $html_address .= '<div class="col-md-12">';
  $html_address .= '<div class="form-group">';
  $html_address .= $continue_button;
  $html_address .= '</div>';
  $html_address .= '</div>';


  $html_address .= '</div>';
  $html_address .= '</form>';

  add_action( 'wp_footer', function(){
    echo '<script type="text/javascript">
      function stripePaymentForm(){
        var ammount = jQuery("#amount").val();
        var final_ammount = ammount*100;
        StripeCheckout.open({
          key: "'. esc_html(get_option('stripe_secret_key')) .'",
          address: false,
          amount: final_ammount,
          currency: "'. esc_html(get_option('paypal_curreny_code')) .'",
          name: "'. esc_html(get_option('stripe_site_name')) .'",
          image: "'. esc_url(get_option('stripe_logo')) .'",
          description: "'. esc_html(get_option('stripe_desc')) .'",
          panelLabel: "Fund Now",
          token: function(response) {
            jQuery("#card_token").val(response.id);
            jQuery("#stripeEmail").val(response.email);
            submitPaymentForm();
          }
        });
      }
    </script>';
  }, 100 );

  return $html_address.$continue_button_logout;
}


/*-----------------------------------*
      Ajax Payment
*------------------------------------*/

add_action('wp_ajax_fund_paymet_form_submit','fund_paymet_form_submit');
add_action('wp_ajax_nopriv_fund_paymet_form_submit','fund_paymet_form_submit');


function fund_paymet_form_submit()
{
  $report = array(
    'status' => false,
    'msg'   => 'There is an error in processing, try again later'
  );

  if (!wp_verify_nonce($_POST['wpnonce'],'payment_form_submit')) {
    echo json_encode($report); die();
  }

  if (!is_user_logged_in()) {
    echo json_encode($report); die();
  }

  $user_id = get_current_user_id(); // current logged in user

  parse_str( $_POST['data'], $info );

  $user_meta['first_name']    = esc_html( $info['first_name'] );
  $user_meta['last_name']     = esc_html( $info['last_name'] );
  $user_meta['email']       = sanitize_email( $info['email'] );
  $user_meta['address1']      = esc_html( $info['address1'] );
  $user_meta['address2']      = esc_html( $info['address2'] );
  $user_meta['city']        = esc_html( $info['city'] );
  $user_meta['state']       = esc_html( $info['state'] );
  $user_meta['zip']         = esc_html( $info['zip'] );
  $user_meta['country']       = esc_html( $info['country'] );
  
  // user meta fields
  foreach ($user_meta as $key => $meta) {
    if (isset($info[$key])) {
      update_user_meta( $user_id, 'startup_'.$key, $meta );
    }
  }

  if ($info['gateway_type'] == 'paypal')
  {
    paypal_payment_submit( $info, $user_meta ); //paypal payment
  }
  elseif ($info['gateway_type'] == 'card')
  {
    stripe_card_payment( $info ); // stripe or card payment
  }

  echo json_encode($report); die();
}


/* ************************************** */
//      Paypal Payment
/* ************************************** */
function paypal_payment_submit( $info, $user_meta ) {

  if( get_option('paypal_payment_type', true) == 'adaptive' ){

    /* ---------------------------------------- */
    // PayPal Platform Parallel Payment Module
    /* ---------------------------------------- */
    $actionType     = "PAY";
    $cancelUrl      = esc_url(get_option("payment_cancel_page")); // TODO - If you are not executing the Pay call for a preapproval,
                                      // then you must set a valid cancelUrl for the web approval flow
                                      // that immediately follows this Pay call
    $returnUrl      = esc_url(get_option("payment_success_page"));  // TODO - If you are not executing the Pay call for a preapproval,
                                      // then you must set a valid returnUrl for the web approval flow
                                      // that immediately follows this Pay call
    $currencyCode   = esc_html(get_option("paypal_curreny_code"));

    $userid = get_post_field( 'post_author', $info['item_number'] );
    
    $receiverEmailArray = array(
        sanitize_email( get_option("paypal_email_address") ),
        sanitize_email( get_user_meta( $userid, 'paypal_email', true) )
        );



    $amount = esc_html( $info['amount'] );

    $admin_percentage = get_option( "donate_page_percentage",true );
    if( $admin_percentage == "" ){ $admin_percentage = 0; }
    $author_commision = 100 - $admin_percentage;

    $author_commision = round( $author_commision*( $amount/100 ), 2 );
    $admin_percentage = $amount - $author_commision;  

    $receiverAmountArray = array(
        $admin_percentage,
        $author_commision
        );

    // for parallel payment, no primary indicators are needed, so set empty array
    $receiverPrimaryArray = array();

    $receiverInvoiceIdArray = array(
        $info['item_number'].'.'.get_current_user_id().'.'.rand(1000,9000).rand(1000,9000),
        $author_commision.rand(1000,9000).rand(1000,9000)
        );

    $senderEmail          = "";   // TODO - If you are executing the Pay call against a preapprovalKey, you should set senderEmail
                          //        It is not required if the web approval flow immediately follows this Pay call
    $feesPayer            = "";
    $ipnNotificationUrl       = plugin_dir_url( __FILE__ ).'adaptive-receiver.php';
    $memo             = "";   // maxlength is 1000 characters
    $pin              = "";   // TODO - If you are executing the Pay call against an existing preapproval
                          // the requires a pin, then you must set this
    $preapprovalKey         = "";   // TODO - If you are executing the Pay call against an existing preapproval, set the preapprovalKey here
    $reverseAllParallelPaymentsOnError  = ""; // TODO - Set this to "true" if you would like each parallel payment to be reversed if an error occurs
                          //        defaults to "false" if you don't specify
    $trackingId           = generateTrackingID(); // generateTrackingID function is found in paypalplatform.php

    //-------------------------------------------------
    // Make the Pay API call
    //
    // The CallPay function is defined in the paypalplatform.php file,
    // which is included at the top of this file.
    //-------------------------------------------------
    $resArray = CallPay ($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray,
                $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray,
                $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey,
                $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId
    );

    $ack = strtoupper($resArray["responseEnvelope.ack"]);
    //echo $ack;
    

    if($ack=="SUCCESS")
    {
      if ("" == $preapprovalKey)
      {
        $cmd = "cmd=_ap-payment&paykey=" . urldecode($resArray["payKey"]);
        
        $redirect_url = RedirectToPayPal ( $cmd );
        $report = array(
          'status'  => true,
          'msg'     => 'success',
          'redirect'  => $redirect_url
        );
        echo json_encode($report); die();
      }
    }

  }else{

    /* ---------------------------------------- */
    // PayPal Platform Single Payment Module
    /* ---------------------------------------- */

    if( get_option('paypal_mode') == "real" ) {
      $paypal_url = "https://www.paypal.com/cgi-bin/webscr?";
    } else {
      $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&";
    }

    $user_id = get_current_user_id();

    $notify_url_link =  plugin_dir_url( __FILE__ ).'receiver.php';

    $oder_data['cmd']         = '_xclick';
    $oder_data['upload']      = 1;
    $oder_data['business']      = sanitize_email(get_option("paypal_email_address"));
    $oder_data['item_name']     = esc_html($info['item_name']);
    $oder_data['item_number']     = esc_html($info['item_number']);
    $oder_data['amount']      = esc_html($info['amount']);
    $oder_data['currency_code']   = esc_html(get_option("paypal_curreny_code"));
    $oder_data['custom']      = $user_id;
    $oder_data['invoice']       = time().rand( 1000 , 9999 );
    $oder_data['notify_url']    = esc_url($notify_url_link);
    $oder_data['return']      = esc_url(get_option("payment_success_page"));
    $oder_data['cancel_return']   = esc_url(get_option("payment_cancel_page"));

    $order = array_merge( $oder_data, $user_meta );

    $http_input = http_build_query( $order, '', '&' );

    $redirect_url = $paypal_url . $http_input;

    $report = array(
      'status'  => true,
      'msg'     => 'success',
      'redirect'  => $redirect_url
    );

    echo json_encode($report); die();

  }


}



/* ************************************** */
//      Stripe payment
/* ************************************** */
function stripe_card_payment( $info )
{
  $report = array(
    'status' => false,
    'msg'   => 'There is an error in processing, try again later'
  );

  $user_id = get_current_user_id();

  \Stripe\Stripe::setApiKey("sk_test_lC6IlLsQ7Mp7CIS1QR6yM4Nn"); // intiatlize stripe api

  $stripeEmail  = sanitize_email( $info['stripeEmail'] ); // useremail
  $token      = esc_html( $info['card_token'] ); // card token by stripe

  $new_customer = \Stripe\Customer::create( array(
    'email' => $stripeEmail,
    'card'  => $token
  ));

  $amount = esc_html( $info['amount'] );

  $final_ammount = $amount * 100;

  try {
    $charge = \Stripe\Charge::create( array(
          'amount'      => $final_ammount, // amount in cents
          'currency'    => esc_html(get_option('paypal_curreny_code')),
          'customer'    => $new_customer['id'],
          'description' => $info['item_name'],
          'metadata'    => array(
            'item_name'   => esc_html($info['item_name']),
            'item_number'   => esc_html($info['item_number']),
            'user_id'     => $user_id,
            'first_name'  => esc_html($info['first_name']),
            'last_name'   => esc_html($info['last_name']),
            'email'     => sanitize_email( $info['email'] ),
            'address1'    => esc_html($info['address1']),
            'address2'    => esc_html($info['address2']),
            'city'      => esc_html($info['city']),
            'state'     => esc_html($info['state']),
            'country'     => esc_html($info['country'])
            )
          )
    );

    $report = array(
      'status'  => true,
      'msg'     => 'success',
      'redirect'  => esc_url(get_option("payment_success_page"))
    );

    echo json_encode($report);die();
  }
  catch (Exception $e)
  {
    $e = $e->getJsonBody();
    echo json_encode($report);die();
  }
}