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


if(!module_finance::can_i('view','Finance Upcoming')){
    redirect_browser(_BASE_HREF);
}

if(isset($_REQUEST['finance_recurring_id']) && $_REQUEST['finance_recurring_id'] && isset($_REQUEST['record_new'])){
    include(module_theme::include_ucm(dirname(__FILE__).'/finance_edit.php'));
}else if(isset($_REQUEST['finance_recurring_id']) && $_REQUEST['finance_recurring_id']){
    //include("recurring_edit.php");
    include(module_theme::include_ucm(dirname(__FILE__).'/recurring_edit.php'));
}else{
    //include("recurring_list.php");
    include(module_theme::include_ucm(dirname(__FILE__).'/recurring_list.php'));
}