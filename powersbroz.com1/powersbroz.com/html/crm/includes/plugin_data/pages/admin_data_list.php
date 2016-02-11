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

// include this file to list some type of data
// supports different types of lists, everything from a major table list down to a select dropdown list

$display_type = 'table';
$allow_search = true;


switch($display_type){
	case 'table':
		
		$data_types = $module->get_data_types();
		foreach($data_types as $data_type){
			$data_type_id = $data_type['data_type_id'];
            if(isset($_REQUEST['data_type_id']) && $data_type_id != $_REQUEST['data_type_id'])continue;

            include('admin_data_list_type.php');

		}
		
		break;
	case 'select':
		
		break;
	default:
		echo 'Display type: '.$display_type.' unknown.';
		break;
}