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

//module_config::register_css('theme','metis_style.css',full_link('/includes/plugin_theme/themes/metis/css/metis_style.css'));

if(!isset($_REQUEST['display_mode']) || (isset($_REQUEST['display_mode']) && $_REQUEST['display_mode']!='iframe' && $_REQUEST['display_mode']!='ajax')){
    $_REQUEST['display_mode'] = 'metis';
}
require_once(module_theme::include_ucm('includes/plugin_theme/themes/metis/metis_functions.php'));
    /**
     * <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/main.css"/>
        <link rel="stylesheet" href="assets/lib/Font-Awesome/css/font-awesome.css"/>

        <link rel="stylesheet" href="assets/css/theme.css">
<script src="assets/lib/modernizr-build.min.js"></script>
     *
     * <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/lib/jquery.min.js"><\/script>')</script>
        <script src="assets/lib/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/js/style-switcher.js"></script>
        <script src="assets/js/main.js"></script>
     */
    module_config::register_css('theme','bootstrap.css',full_link('/includes/plugin_theme/themes/metis/lib/bootstrap/css/bootstrap.min.css'),5);
    module_config::register_css('theme','main.css',full_link('/includes/plugin_theme/themes/metis/css/main.css'),6);
    module_config::register_css('theme','font-awesome.css',full_link('/includes/plugin_theme/themes/metis/lib/font-awesome-4.0.3/css/font-awesome.css'),7);
    module_config::register_css('theme','theme.css',full_link('/includes/plugin_theme/themes/metis/css/theme.css'),8);
    if(isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'],_EXTERNAL_TUNNEL) || strpos($_SERVER['REQUEST_URI'],_EXTERNAL_TUNNEL_REWRITE))){
        module_config::register_css('theme','external.css',full_link('/includes/plugin_theme/themes/metis/css/external.css'),100);
    }

    module_config::register_js('theme','bootstrap.js',full_link('/includes/plugin_theme/themes/metis/lib/bootstrap/js/bootstrap.min.js'));
    module_config::register_js('theme','main.js',full_link('/includes/plugin_theme/themes/metis/js/main.js'));
    module_config::register_js('theme','metis.js',full_link('/includes/plugin_theme/themes/metis/js/metis.js'));
    //module_config::register_js('theme','config.js',full_link('/includes/plugin_theme/themes/whitelabel1/js/config.js'));
    //module_config::register_js('theme','script.js',full_link('/includes/plugin_theme/themes/whitelabel1/js/script.js'));