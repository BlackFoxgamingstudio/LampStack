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


if(!module_config::can_i('view','Settings')){
    redirect_browser(_BASE_HREF);
}

print_heading('Help Settings');

module_config::print_settings_form(
    array(
        array(
            'key'=>'help_only_for_admin',
            'default'=>1,
            'type'=>'checkbox',
            'description'=>'Only show help menu for Super Administrator.',
	        'help' => 'By default only the Super Administrator (first user created) can see the help documentation. If this option is disabled you will still need to give each User Role access to "view help" for them to see the "help" menu correctly. Please note that the help documentation may contain branding.'
        ),
    )
);
