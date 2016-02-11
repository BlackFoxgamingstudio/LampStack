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


if(isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != ''){
    $product_id = (int)$_REQUEST['product_id'];
	$product = module_product::get_product($product_id);
    include('product_admin_edit.php');
}else{
	include('product_admin_list.php');
}
