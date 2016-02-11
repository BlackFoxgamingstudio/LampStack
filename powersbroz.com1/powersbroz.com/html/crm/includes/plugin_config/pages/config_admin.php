
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

$module->page_title = 'Admin';

if(module_config::can_i('view','Settings')){
    $links = array(
        array(
            "name"=>"System Settings",
            'm' => 'config',
            'p' => 'config_basic_settings',
            'force_current_check' => true,
            //'default_page' => 'config_basic_settings',
            'order' => 1, // at start.
            'menu_include_parent' => 0,
            'allow_nesting' => 1,
        ),
        array(
            "name"=>"Menu",
            'm' => 'config',
            'p' => 'config_menu',
            'force_current_check' => true,
            'order' => 9994,
            'menu_include_parent' => 0,
            'allow_nesting' => 1,
        ),
        array(
            "name"=>"Payments",
            'm' => 'config',
            'p' => 'config_payment',
            'force_current_check' => true,
            'order' => 9995,
            'menu_include_parent' => 0,
            'allow_nesting' => 1,
        ),
        array(
            "name"=>"Advanced",
            'm' => 'config',
            'p' => 'config_settings',
            'force_current_check' => true,
            //'default_page' => 'config_settings',
            'order' => 9999, // at end.
            'menu_include_parent' => 0,
            'allow_nesting' => 1,
        ),
    );
}

if(module_config::can_i('view','Upgrade System')){
    $links[] = array(
        "name"=>"Upgrade",
            'm' => 'config',
            'p' => 'config_upgrade',
            'force_current_check' => true,
            'order' => 9998, // at end.
            'menu_include_parent' => 0,
            'allow_nesting' => 1,
        );
}

?>