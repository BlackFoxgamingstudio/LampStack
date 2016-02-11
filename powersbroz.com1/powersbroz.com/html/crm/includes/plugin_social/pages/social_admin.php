<!-- show a list of tabs for all the different social methods, as menu hooks -->

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

$module->page_title = _l('Social');


$links = array();
if(module_social::can_i('view','Combined Comments','Social','social')){
	$links [] = array(
        "name"=>_l('Inbox'),
        'm' => 'social',
        'p' => 'social_messages',
		'args' => array(
			'combined' => 1,
			'social_twitter_id' => false,
			'social_facebook_id' => false,
		),
        'force_current_check' => true,
        //'current' => isset($_GET['combined']),
        'order' => 1, // at start
        'menu_include_parent' => 0,
        'allow_nesting' => 1,
    );

	//if(isset($_GET['combined'])){
	//	include('social_messages.php');
	//}
}