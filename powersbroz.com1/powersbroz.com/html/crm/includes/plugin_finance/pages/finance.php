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
//include("top_menu.php");




$module->page_title = _l('Finance');


$links = array();
$menu_position = 1;

array_unshift($links,array(
    "name"=>"Transactions",
    'm' => 'finance',
    'p' => 'finance',
    'default_page' => 'finance_list',
    'order' => $menu_position++,
    'menu_include_parent' => 0,
    'allow_nesting' => 0,
    'args' => array('finance_id'=>false),
));
if(module_finance::can_i('view','Finance Upcoming')){
    array_unshift($links,array(
        "name"=>"Upcoming Payments",
        'm' => 'finance',
        'p' => 'recurring',
        'order' => $menu_position++,
        'menu_include_parent' => 0,
        'allow_nesting' => 1,
        'args' => array('finance_id'=>false,'finance_recurring_id'=>false),
    ));
}

?>