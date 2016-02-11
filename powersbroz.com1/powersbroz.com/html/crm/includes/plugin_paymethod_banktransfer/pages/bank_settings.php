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


if(!module_config::can_i('view','Settings')){
    redirect_browser(_BASE_HREF);
}

print_heading('Bank Transfer Settings');
module_config::print_settings_form(
    array(
         array(
            'key'=>'payment_method_banktransfer_enabled',
            'default'=>1,
             'type'=>'checkbox',
             'description'=>'Enable Payment Method',
         ),
         array(
            'key'=>'payment_method_banktransfer_enabled_default',
            'default'=>1,
             'type'=>'checkbox',
             'description'=>'Available By Default On Invoices',
	         'help' => 'If this option is enabled, all new invoices will have this payment method available. If this option is disabled, it will have to be enabled on individual invoices.'
         ),
         array(
            'key'=>'payment_method_banktransfer_label',
            'default'=>'Bank Transfer',
             'type'=>'text',
             'description'=>'Name this payment method',
         ),
    )
);

print_heading('Bank Transfer Templates');
echo module_template::link_open_popup('paymethod_banktransfer');
echo module_template::link_open_popup('paymethod_banktransfer_details');
?>
