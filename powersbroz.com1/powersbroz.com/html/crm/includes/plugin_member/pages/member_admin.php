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


if(isset($_REQUEST['member_id'])){
    $links = array();

    $member_id = $_REQUEST['member_id'];
    if($member_id && $member_id != 'new'){
        $member = module_member::get_member($member_id);
        // we have to load the menu here for the sub plugins under member
        // set default links to show in the bottom holder area.

        array_unshift($links,array(
            "name"=>_l('Member:').' <strong>'.htmlspecialchars($member['first_name'] .' '.$member['last_name']).'</strong>',
            "icon"=>"images/icon_arrow_down.png",
            'm' => 'member',
            'p' => 'member_admin',
            'default_page' => 'member_admin_edit',
            'order' => 1,
            'menu_include_parent' => 0,
        ));
    }else{
        $member = array(
            'name' => 'New Member',
        );
        array_unshift($links,array(
            "name"=>"New Member Details",
            "icon"=>"images/icon_arrow_down.png",
            'm' => 'member',
            'p' => 'member_admin',
            'default_page' => 'member_admin_edit',
            'order' => 1,
            'menu_include_parent' => 0,
        ));
    }

}else{
    include('member_admin_list.php');
}