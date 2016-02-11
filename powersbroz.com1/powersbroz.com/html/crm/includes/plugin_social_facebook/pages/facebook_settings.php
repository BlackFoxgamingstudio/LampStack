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


if(_DEMO_MODE){
	?>
	<p>Demo Mode Notice: <strong>This is a public demo. Please only use TEST accounts here as others will see them.</strong></p>
	<?php
}


if(isset($_REQUEST['social_facebook_id']) && !empty($_REQUEST['social_facebook_id'])){
    $social_facebook_id = (int)$_REQUEST['social_facebook_id'];
	$social_facebook = module_social_facebook::get($social_facebook_id);
    include('facebook_account_edit.php');
}else{
	include('facebook_account_list.php');
}
