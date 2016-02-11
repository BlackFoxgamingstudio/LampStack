<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */

// do a settings page with a link to this page exmplainint the signpu process
// http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API.html#integration_overview
// once you have signed up at ( http://checkout.google.com/sell/signup ) find your  merchant id and key on the Settings > Integration page ( https://checkout.google.com/sell/settings?section=Integration )

class module_paymethod_google extends module_base{

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function init(){
        $this->version = 2.22;
		$this->module_name = "paymethod_google";
		$this->module_position = 8882;

        // 2.2 - perm fix
        // 2.21 - 2013-07-29 - new _UCM_SECRET hash in config.php
        // 2.22 - 2015-03-14 - better default payment method options

	}

    public function pre_menu(){

        if(module_config::can_i('view','Settings')){
            $this->links[] = array(
                "name"=>"Google Checkout",
                "p"=>"google_settings",
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_payment',  // which page this link will be automatically added to.
                'menu_include_parent' => 1,
            );
        }
    }


    public function handle_hook($hook){
        switch($hook){
            case 'get_payment_methods':
                return $this;
                break;
        }
    }

    public function is_method($method){
        return $method=='online';
    }
    public static function is_enabled(){
        return module_config::c('payment_method_google_enabled',1);
    }

	public function is_allowed_for_invoice($invoice_id){
	    if(!self::is_enabled())return false;
	    $old_default = module_config::c('__inv_google_'.$invoice_id);
	    if($old_default !== false){
		    $this->set_allowed_for_invoice($invoice_id,$old_default);
		    delete_from_db('config','key','__inv_google_'.$invoice_id);
		    module_cache::clear('config');
		    return $old_default;
	    }
	    // check for manually enabled invoice payment method.
	    $invoice_payment_methods = module_invoice::get_invoice_payment_methods($invoice_id);
	    if(isset($invoice_payment_methods['google']))return $invoice_payment_methods['google']['enabled'];
        return module_config::c('payment_method_google_enabled_default',1);
    }
    public function set_allowed_for_invoice($invoice_id,$allowed=1){
	    $sql = "REPLACE INTO `"._DB_PREFIX."invoice_payment_method` SET `invoice_id` = ".(int)$invoice_id.", `payment_method` = 'google', `enabled` = ".(int)$allowed;
        query($sql);
    }
	
    public static function get_payment_method_name(){
        return module_config::s('payment_method_google_label','Google Checkout');
    }


    public function get_invoice_payment_description($invoice_id,$method=''){



    }

    public static function get_merchant_id(){
        return self::is_sandbox() ? module_config::c('payment_method_google_pmid_s') : module_config::c('payment_method_google_pmid');
    }
    public static function get_merchant_key(){
        return self::is_sandbox() ? module_config::c('payment_method_google_pmkey_s') : module_config::c('payment_method_google_pmkey');
    }
    public static function verify_merchant_account(){
        $url = '@checkout.google.com/api/checkout/v2/request/Merchant/';
        if(self::is_sandbox()){
            $url = '@sandbox.google.com/checkout/api/checkout/v2/request/Merchant/';
        }
        $full_url = 'https://'.self::get_merchant_id().':'.self::get_merchant_key().$url.self::get_merchant_id();
        $ch = curl_init($full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '<hello xmlns="http://checkout.google.com/schema/2"/>');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);

        if(preg_match('#<bye#',$output)){
            return true;
        }else{
            return $output;
        }

    }

    public static function add_payment_data($invoice_payment_id,$key,$val){
        $payment = module_invoice::get_invoice_payment($invoice_payment_id);
        $payment_data = @unserialize($payment['data']);
        if(!is_array($payment_data))$payment_data = array();
        if(!isset($payment_data[$key]))$payment_data[$key]=array();
        $payment_data[$key][] = $val;
        update_insert('invoice_payment_id',$invoice_payment_id,'invoice_payment',array('data'=>serialize($payment_data)));
    }
    
    public static function start_payment($invoice_id,$payment_amount,$invoice_payment_id,$user_id=false){
        if($invoice_id && $payment_amount && $invoice_payment_id){
            // we are starting a payment via google!
            // setup a pending payment and redirect to google.
            // save some initial data into this payment record.
            self::add_payment_data($invoice_payment_id,'redirect_time',time());

            $invoice_data = module_invoice::get_invoice($invoice_id);

            if(!$user_id)$user_id = $invoice_data['user_id'];
            if(!$user_id)$user_id = module_security::get_loggedin_id();

            $description = _l('Payment for invoice %s',$invoice_data['name']);
            self::google_redirect($description,$payment_amount,$user_id,$invoice_payment_id,$invoice_id,$invoice_data['currency_id']);
            return true;
        }
        return false;
    }


    public static function is_sandbox(){
        return module_config::c('payment_method_google_sandbox',0);
    }

    public static function google_redirect($description,$amount,$user_id,$invoice_payment_id,$invoice_id,$currency_id){

        chdir(dirname(__FILE__)); //'includes/plugin_paymethod_google/');
        require_once('library/googlecart.php');
        require_once('library/googleitem.php');


        $server_type = self::is_sandbox() ? "sandbox" : '';
        $currency = module_config::get_currency($currency_id);
        self::add_payment_data($invoice_payment_id,'log','Starting payment of '.$server_type.' in currency '.$currency['code']);
        $cart = new GoogleCart(self::get_merchant_id(), self::get_merchant_key(), $server_type, $currency['code']);
        $total_count = 1;
        //  Check this URL for more info about the two types of digital Delivery
        //  http://code.google.com/apis/checkout/developer/Google_Checkout_Digital_Delivery.html

        //  Key/URL delivery
        self::add_payment_data($invoice_payment_id,'log','Adding '.$total_count.'x '.$description.' ('.$amount.' '.$currency['code'].')');
        $item_1 = new GoogleItem($description,      // Item name
                           "", // Item description
                           $total_count, // Quantity
                           $amount); // Unit price
        //$item_1->SetURLDigitalContent(module_invoice::link_receipt($invoice_payment_id),
        $item_1->SetURLDigitalContent(module_invoice::link_public_print($invoice_id),
                                '',
                                _l("Payment Receipt"));
        $cart->AddItem($item_1);

        $private_data = new MerchantPrivateData(array(
                                          'invoice_id'=>$invoice_id,
                                          'amount'=>$amount,
                                          'currency_id'=>$currency_id,
                                          'invoice_payment_id'=>$invoice_payment_id,
                                      ));
        $cart->SetMerchantPrivateData($private_data);


        // Specify <edit-cart-url>
        $cart->SetEditCartUrl(module_invoice::link_public($invoice_id));

        // Specify "Return to xyz" link
        $cart->SetContinueShoppingUrl(module_invoice::link_public($invoice_id));
        

        // Request buyer's phone number
        //$cart->SetRequestBuyerPhone(true);

        // This will do a server-2-server cart post and send an HTTP 302 redirect status
        // This is the best way to do it if implementing digital delivery
        // More info http://code.google.com/apis/checkout/developer/index.html#alternate_technique
        list($status, $error) = $cart->CheckoutServer2Server();
        // if i reach this point, something was wrong
        echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
        echo "<br />Error message:<br />";
        echo $error;
        exit;

    }



    public function external_hook($hook){

        switch($hook){
            case 'notification':

                if(!isset($_REQUEST['serial-number']) || !$_REQUEST['serial-number']){
                    send_error('Google API not setup correctly. Please ensure API callback URL is set correctly as per instructions');
                    exit;
                }

                chdir(dirname(__FILE__)); //'includes/plugin_paymethod_google/');

                require_once('library/googleresponse.php');
                //require_once('library/googlemerchantcalculations.php');
                require_once('library/googleresult.php');
                require_once('library/googlerequest.php');
                require_once('library/googlenotification.php');
                require_once('library/googlenotificationhistory.php');

                define('RESPONSE_HANDLER_ERROR_LOG_FILE', _UCM_FOLDER . '/temp/googleerror.log');
                define('RESPONSE_HANDLER_LOG_FILE', _UCM_FOLDER . '/temp/googlemessage.log');

                $server_type = self::is_sandbox() ? "sandbox" : '';
                //$currency = module_config::get_currency($currency_id);

                //$Gresponse = new GoogleResponse(self::get_merchant_id(), self::get_merchant_key());
                $Grequest = new GoogleRequest(self::get_merchant_id(), self::get_merchant_key(), $server_type);

                $GNotificationHistory = new GoogleNotificationHistoryRequest(self::get_merchant_id(), self::get_merchant_key(), $server_type);
                $response = $GNotificationHistory->SendNotificationHistoryRequest($_REQUEST['serial-number']);
                $xml_response_status = $response[0];
                $xml_response = $response[1];

                $response = new SimpleXMLElement($xml_response);
                $root = $response->getName();
                // debugging
                //send_error('xml2: '.$root.var_export($response,true));

                //$Gresponse->SetMerchantAuthentication(self::get_merchant_id(), self::get_merchant_key());
                //$Gresponse->SendAck();

                $google_order_number = (string)$response->{'google-order-number'};
                if($google_order_number){
                    $invoice_payment_data = get_single('invoice_payment','other_id',$google_order_number);
                }else{
                    $invoice_payment_data = false;
                    send_error('Google payment API failure, no Google Order Number located');
                }

                if($invoice_payment_data && $invoice_payment_data['invoice_payment_id']){
                    self::add_payment_data($invoice_payment_data['invoice_payment_id'],'log','Recorded an API hit: '.$root);
                }

                switch ($root) {
                    case "new-order-notification": {
                      // an order has been placed.
                        // dont really need to do anything.
                        // we grab the merchant private data from this bit
                        // it contains the private id etc..
                        if($google_order_number && $response->{'shopping-cart'}->{'merchant-private-data'}){
                            $private_data = (array)$response->{'shopping-cart'}->{'merchant-private-data'};
                            $invoice_id = (int)$private_data['invoice_id'];
                            $invoice_payment_id = (int)$private_data['invoice_payment_id'];
                            if($invoice_id>0&&$invoice_payment_id>0){
                                self::add_payment_data($invoice_payment_id,'log','Found Google Order Number: '.$google_order_number);
                                $sql="UPDATE `"._DB_PREFIX."invoice_payment` SET `other_id` = '".mysql_real_escape_string($google_order_number)."' WHERE invoice_id = '$invoice_id' AND invoice_payment_id = '$invoice_payment_id'";
                                query($sql);
                            }
                        }
                      
                      break;
                    }
                    case "order-state-change-notification": {
                      
                      $new_financial_state = (string)$response->{'new-financial-order-state'};

                        if($invoice_payment_data && $invoice_payment_data['invoice_payment_id']){
                            self::add_payment_data($invoice_payment_data['invoice_payment_id'],'log','Recorded an order status change to: '.$new_financial_state);
                        }

                      switch($new_financial_state) {
                        case 'REVIEWING': {
                          break;
                        }
                        case 'CHARGEABLE': {
                            if($invoice_payment_data && $invoice_payment_data['invoice_payment_id']){
                                self::add_payment_data($invoice_payment_data['invoice_payment_id'],'log','Charging customers credit card. ');
                            }
                            // process and charge the order:
                          $Grequest->SendProcessOrder($google_order_number);
                          $Grequest->SendChargeOrder($google_order_number,'');

                          break;
                        }
                        case 'CHARGING': {
                          break;
                        }
                        case 'CHARGED': {
                          break;
                        }
                        case 'PAYMENT_DECLINED': {
                          break;
                        }
                        case 'CANCELLED': {
                          break;
                        }
                        case 'CANCELLED_BY_GOOGLE': {
                          //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
                          //    "Sorry, your order is cancelled by Google", true);
                          break;
                        }
                        default:
                          break;
                      }

                      break;
                    }
                    case "charge-amount-notification": {
                        // payment has been made!
                        // update the order.


                      $Grequest->SendArchiveOrder($google_order_number );

                        if($invoice_payment_data && $invoice_payment_data['invoice_payment_id']){
                            self::add_payment_data($invoice_payment_data['invoice_payment_id'],'log','Received a payment of '.$response->{'latest-charge-amount'}.' and assigning it to this invoice.');
                        update_insert("invoice_payment_id",$invoice_payment_data['invoice_payment_id'],"invoice_payment",array(
                                                                              'date_paid' => date('Y-m-d'),
                                                                     ));
                        }

                      break;
                    }
                    case "chargeback-amount-notification": {
                      
                      break;
                    }
                    case "refund-amount-notification": {
                      
                      break;
                    }
                    case "risk-information-notification": {
                      
                      break;
                    }
                    default:
                      //$Gresponse->SendBadRequestStatus("Invalid or not supported Message");
                      break;
                }
                break;
        }
    }

    public static function link_callback($h=false){
        if($h){
            return md5('s3cret7hash for callback url '._UCM_SECRET.' ');
        }
        return full_link(_EXTERNAL_TUNNEL_REWRITE.'m.paymethod_google/h.notification/hash.'.self::link_callback(true));
    }
}


/* In case the XML API contains multiple open tags
 with the same value, then invoke this function and
 perform a foreach on the resultant array.
 This takes care of cases when there is only one unique tag
 or multiple tags.
 Examples of this are "anonymous-address", "merchant-code-string"
 from the merchant-calculations-callback API
*/
function get_arr_result($child_node) {
    $result = array();
    if(isset($child_node)) {
      if(is_associative_array($child_node)) {
        $result[] = $child_node;
      }
      else {
        foreach($child_node as $curr_node){
          $result[] = $curr_node;
        }
      }
    }
    return $result;
}

/* Returns true if a given variable represents an associative array */
function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
}