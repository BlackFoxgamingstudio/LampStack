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

$invoice_safe = true;

if(isset($_REQUEST['print'])){
    include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_print.php"));
        //include('invoice_admin_print.php');
}else if(isset($_REQUEST['invoice_id'])){

    if(isset($_REQUEST['email'])){
        include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_email.php"));
        //include('invoice_admin_email.php');
    }else{
        /*if(module_security::getlevel() > 1){
            include('invoice_customer_view.php');
        }else{*/
            include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_edit.php"));
            //include("invoice_admin_edit.php");
        /*}*/
    }

}else{

    include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_list.php"));
	//include("invoice_admin_list.php");
	
} 

