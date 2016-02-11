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

$access = true;


switch($table_name){
    case 'invoice':
    default:
        // check if current user can access this invoice.
        if($data && isset($data['customer_id']) && (int)$data['customer_id']>0){
            $valid_customer_ids = module_security::get_customer_restrictions();
            if($valid_customer_ids){
                $access = isset($valid_customer_ids[$data['customer_id']]);
                if(!$access)return false;
            }
        }
        break;
}