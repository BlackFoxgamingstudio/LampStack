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

if(module_data::can_i('edit',_MODULE_DATA_NAME)){

	// show all datas.
	if(isset($_REQUEST['data_field_id']) && $_REQUEST['data_field_id'] && isset($_REQUEST['data_type_id']) && $_REQUEST['data_type_id']){
		
		include("data_type_admin_field_open.php");
		
	}else if(isset($_REQUEST['data_field_group_id']) && $_REQUEST['data_field_group_id']){
		
		include("data_type_admin_group_open.php");
		
	}else if(isset($_REQUEST['data_type_id']) && $_REQUEST['data_type_id']){
		
		include("data_type_admin_open.php");
		
	}else{
		
		include("data_type_admin_list.php");
	}
	
}