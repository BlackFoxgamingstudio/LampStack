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

if(!module_config::can_i('edit','Settings')){
    redirect_browser(_BASE_HREF);
}


print_heading('Google Checkout/Wallet Settings');?>

<?php if(isset($_REQUEST['test_google'])){

    $result = module_paymethod_google::verify_merchant_account();
    if($result === true){
        set_message('Google checkout details correct!');
    }else{
        set_error('Failed, response from google: '.htmlspecialchars($result));
    }
    redirect_browser($_SERVER['REQUEST_URI']);
} ?>


<?php module_config::print_settings_form(
    array(
         array(
            'key'=>'payment_method_google_enabled',
            'default'=>1,
             'type'=>'checkbox',
             'description'=>'Enable Google Checkout',
         ),
         array(
            'key'=>'payment_method_google_enabled_default',
            'default'=>1,
             'type'=>'checkbox',
             'description'=>'Available By Default On Invoices',
	         'help' => 'If this option is enabled, all new invoices will have this payment method available. If this option is disabled, it will have to be enabled on individual invoices.'
         ),
         array(
            'key'=>'payment_method_google_pmid',
            'default'=>'',
             'type'=>'text',
             'description'=>'Your Merchant ID',
         ),
         array(
            'key'=>'payment_method_google_pmkey',
            'default'=>'',
             'type'=>'text',
             'description'=>'Your Merchant KEY',
         ),
         array(
            'key'=>'payment_method_google_pmid_s',
            'default'=>'',
             'type'=>'text',
             'description'=>'Your SANDBOX Merchant ID (optional)',
         ),
         array(
            'key'=>'payment_method_google_pmkey_s',
            'default'=>'',
             'type'=>'text',
             'description'=>'Your SANDBOX Merchant KEY (optional)',
         ),
         array(
            'key'=>'payment_method_google_sandbox',
            'default'=>0,
             'type'=>'checkbox',
             'description'=>'Use Google Sandbox Mode (for testing payments)',
         ),
    )
); ?>

<?php print_heading('Google Checkout/Wallet setup instructions:');?>

<p>Please signup for a Google payments account here: <a href="http://checkout.google.com/sell/signup">http://checkout.google.com/sell/signup</a> then find your  merchant ID and KEY on the Settings > Integration page. Enter those values on this page. Click the test button to check your settings are correct.</p>

<p><strong>Important:</strong> If you would like the system to <em>automatically</em> record a payment against an invoice then you need to set your Notification Callback API url by following the instructions below (if you do not want to do this then you will have to mark payments against invoices manually each time a customer pays)</p>
<ol>
<li><a href="http://checkout.google.com/sell">Sign in</a> to Google Checkout.</li>
<li>Click the <b>Settings</b> tab.</li>
<li>Click <b>Integration</b>.</li>
<li>Enter this callback URL in the 'API callback URL' box: <strong><?php echo module_paymethod_google::link_callback();?></strong> </li>
<li>Choose the first option "Notification Serial Number"</li>
<li>Click <b>Save</b>.</li>
</ol>
    
<p>More details about signing up for a sandbox account are available here: <a href="http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API.html#integration_overview">http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API.html#integration_overview</a></p>


<form action="" method="post">
    <input type="hidden" name="test_google" value="true">
    <input type="submit" name="go" value="Test Above Settings">
</form>
