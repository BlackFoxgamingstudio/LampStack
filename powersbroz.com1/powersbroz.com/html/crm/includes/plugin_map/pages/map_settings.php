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


$settings = array(
     array(
        'key'=>'enable_customer_maps',
        'default'=>'1',
         'type'=>'checkbox',
         'description'=>'Enable Customer Maps',
     ),
     array(
        'key'=>'google_maps_api_key',
        'default'=>'AIzaSyDFYt1ozmTn34lp96W0AakC-tSJVzEdXjk',
         'type'=>'text',
         'description'=>'Google Maps API Key',
         'help' => 'This is required to get markers displaying on the map. If markers are not displaying please sign up for your own Google Maps/Geocoding API key and put it here.'
     ),
);
module_config::print_settings_form(
    array(
        'heading' => array(
            'title' => 'Map Settings',
            'type' => 'h2',
            'main' => true,
        ),
        'settings' => $settings,
    )
);
