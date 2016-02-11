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

if(!module_social::can_i('edit','Facebook','Social','social')){
    die('No access to Facebook accounts');
}

$social_facebook_id = isset($_REQUEST['social_facebook_id']) ? (int)$_REQUEST['social_facebook_id'] : 0;
$facebook = new ucm_facebook_account($social_facebook_id);

$facebook_page_id = isset($_REQUEST['facebook_page_id']) ? (int)$_REQUEST['facebook_page_id'] : 0;

/* @var $pages ucm_facebook_page[] */
$pages = $facebook->get('pages');
if(!$facebook_page_id || !$pages || !isset($pages[$facebook_page_id])){
	die('No pages found to refresh');
}
?>
Manually refreshing page data...
<?php

$pages[$facebook_page_id]->graph_load_latest_page_data();
