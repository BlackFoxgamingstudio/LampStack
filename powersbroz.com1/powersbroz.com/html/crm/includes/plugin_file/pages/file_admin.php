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

$file_safe = true;
$file_id = isset($_REQUEST['file_id']) ? (int)$_REQUEST['file_id'] : false;

if($file_id && isset($_REQUEST['email'])){

    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_email.php'));

}else if(isset($_REQUEST['file_id'])){


	$ucm_file = new ucm_file( $file_id );
	$ucm_file->check_page_permissions();
	$file    = $ucm_file->get_data();
	$file_id = (int) $file['file_id']; // sanatisation/permission check

	if(isset($_REQUEST['bucket']) || (isset($file['bucket']) && $file['bucket'])){
	    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_bucket.php'));
	}else{
		include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_edit.php'));
	}


}else{
	
    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_list.php'));
	
} 

