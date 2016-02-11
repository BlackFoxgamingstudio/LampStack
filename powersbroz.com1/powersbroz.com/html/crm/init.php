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


require_once("includes/config.php");

if(_DEBUG_MODE && !isset($debug_info))$debug_info=array();

define('_APPLICATION_ID',2621629); // not used any more.
define('_SCRIPT_VERSION','3.528');
// 3.528 - 2016-01-04 - dynamic form fix
// 3.527 - 2015-12-27 - extra field js
// 3.526 - 2015-07-29 - backup js
// 3.525 - 2015-06-07 - settings extra
// 3.524 - 2015-05-04 - responsive ticket css
// 3.523 - 2015-05-03 - responsive
// 3.522 - 2015-04-05 - url help js
// 3.521 - 2015-03-17 - backup js
// 3.52 - 2015-03-08 - ticket bulk
// 3.519 - 2015-03-08 - time_format
// 3.518 - 2015-02-12 - product js defaults
// 3.517 - 2015-02-12 - job task css fix
// 3.516 - 2015-02-10 - job discussion js
// 3.515 - 2015-02-08 - job discussion js
// 3.514 - 2015-01-26 - dashboard widgets save position
// 3.513 - 2014-12-17 - signup form on login
// 3.512 - 2014-11-28 - check
// 3.511 - 2014-11-19 - adminlte css
// 3.51 - 2014-11-17 - upgrade faster
// 3.5 - 2014-11-05 - ticket js fix
// 3.499 - 2014-10-13 - encrypt adminlte fix
// 3.498 - 2014-09-18 - job_send_task_completion_email_automatically
// 3.497 - 2014-09-02 - calendar am/pm fix
// 3.496 - 2014-08-12 - social js fix
// 3.495 - 2014-08-10 - upgrade.js
// 3.494 - 2014-08-09 - backup js fix METHOD/TYPE ajax
// 3.493 - 2014-08-08 - backup feature
// 3.492 - 2014-08-06 - js fix
// 3.491 - 2014-07-12 - select max width css
// 3.49 - 2014-07-09 - signature
// 3.489 - 2014-07-05 - translations and js/css combine
// 3.488 - 2014-05-27 - job discussion
// 3.487 - 2014-04-05 - social scripts
// 3.486 - 2014-02-18 - timer fix
// 3.485 - 2014-01-21 - js encrypt library
// 3.484 - 2013-12-30 - ui fix for datepicker
// 3.483 - 2013-11-19 - working on new UI
// 3.482 - 2013-11-15 - working on new UI
// 3.481 - invoice tax incremental
// 3.48 - job create invoice
// 3.47 - finance tax
// 3.46 - whitelable content_box_header spacing
// 3.45 - ticket bulk operations
// 3.42 - invoice product adjustments

if(!defined('_UCM_SECRET')){
    define('_UCM_SECRET',_UCM_FOLDER);
}

if(!isset($_SERVER['REQUEST_URI'])){
//ISAPI_Rewrite 3.x
    if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
    }
    //ISAPI_Rewrite 2.x w/ HTTPD.INI configuration
    else if (isset($_SERVER['HTTP_REQUEST_URI'])){
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_REQUEST_URI'];
        //Good to go!
    }
    //ISAPI_Rewrite isn't installed or not configured
    else{
        //Someone didn't follow the instructions!
        if(isset($_SERVER['SCRIPT_NAME']))
            $_SERVER['HTTP_REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
        else
            $_SERVER['HTTP_REQUEST_URI'] = $_SERVER['PHP_SELF'];
        if($_SERVER['QUERY_STRING']){
            $_SERVER['HTTP_REQUEST_URI'] .=  '?' . $_SERVER['QUERY_STRING'];
        }
        //WARNING: This is a workaround!
        //For guaranteed compatibility, HTTP_REQUEST_URI or HTTP_X_REWRITE_URL *MUST* be defined!
        //See product documentation for instructions!
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_REQUEST_URI'];
    }
}

// oldschool setups:
if(get_magic_quotes_gpc()){
    function stripslashes_deep(&$value){
        $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
        return $value;
    }
	stripslashes_deep($_GET);
    stripslashes_deep($_POST);
}
if(_DEBUG_MODE){
    $debug_info[] = array(
        'time' => microtime(true),
        'title' => 'Including core files',
        'mem' => function_exists('memory_get_usage') ? round(memory_get_usage()/1048576,4) : '',
    );
}
require_once("includes/functions.php");
require_once("includes/database.php");
require_once("includes/links.php");
if(_DEBUG_MODE){
    $debug_info[] = array(
        'time' => microtime(true),
        'title' => 'Including plugin files',
        'mem' => function_exists('memory_get_usage') ? round(memory_get_usage()/1048576,4) : '',
    );
}
// include all our plugin files:
require_once("includes/plugin.php");
global $plugins;

$plugins_to_init = isset($plugins_to_init) && is_array($plugins_to_init) ? $plugins_to_init : array();
if(!isset($dont_init_plugins) || $dont_init_plugins == false){
    foreach(glob("includes/plugin_*") as $plugin_dir){
        $plugin_name = str_replace("plugin_", "", basename($plugin_dir));
        if(is_dir($plugin_dir) && is_file($plugin_dir."/".$plugin_name.".php")){
            if(_DEBUG_MODE){
                $debug_info[] = array(
                    'time' => microtime(true),
                    'title' => 'Including plugin file: '.$plugin_name,
                    'data' => $plugin_dir."/".$plugin_name.".php",
                    'mem' => function_exists('memory_get_usage') ? round(memory_get_usage()/1048576,4) : '',
                );
            }
	        $plugin_file_name = $plugin_dir."/".$plugin_name.".php";
	        if(is_file('custom/'.$plugin_dir."/".$plugin_name.".php")){
		        $plugin_file_name = 'custom/'.$plugin_dir."/".$plugin_name.".php";
	        }
            if(require_once $plugin_file_name){
                $plugins_to_init[] = $plugin_name;
            }

        }
    }
}else{
    // EDIT: this won't work in ext.php, we need all plugins to load so that all plugins have a chance to hook into the UI.
    // bah.
    // only init core plugins
    $plugins_to_init[] = 'cache';
    $plugins_to_init[] = 'debug';
    $plugins_to_init[] = 'config';
    $plugins_to_init[] = 'security';
    $plugins_to_init[] = 'language';
	foreach($plugins_to_init as $plugin_to_init){
        $plugin_file_name = 'includes/plugin_'.$plugin_to_init.'/'.$plugin_to_init.'.php';
        if(is_file('custom/'.$plugin_file_name)){
	        $plugin_file_name = 'custom/'.$plugin_file_name;
        }
		require_once $plugin_file_name;
	}
}

if(_DEBUG_MODE){
    if(isset($start_time))module_debug::$start_time = $start_time;
    if(isset($debug_info)){
        foreach($debug_info as $prior_debug_log){
             module_debug::log($prior_debug_log);
        }
    }
    module_debug::log(array(
        'title' => 'Plugins Loaded',
        'data' => implode(', ',$plugins_to_init),
     ));
}


define('_UCM_INSTALLED',is_installed()); // is_installed() will do our db_connection


$plugins = array();
if(_UCM_INSTALLED){
    //$db = db_connect();
    // is_installed() does the db_connect above..
}

// storing sessions in a database, only if it's enabled.

// some hosting accounts dont have default session settings that work :-/
//ini_set('error_reporting',E_ALL);
//ini_set('display_errors',true);
if(!session_id() && (!isset($disable_sessions) || !$disable_sessions)){
    if($plugins_to_init && is_file('includes/plugin_session/session.php')){
	    $plugin_file_name = 'includes/plugin_session/session.php';
        if(is_file('custom/'.$plugin_file_name)){
	        $plugin_file_name = 'custom/'.$plugin_file_name;
        }
		require_once $plugin_file_name;
        $plugins_to_init[] = 'session';
    }
    if(class_exists('module_session') && module_session::is_db_sessions_enabled()){
        // don't set file based sessions
        if(_DEBUG_MODE)module_debug::log(array(
            'title' => 'Loading database sessions',
         ));
        new module_session();
    }else if(is_dir(_UCM_FOLDER . "/temp/") && is_writable(_UCM_FOLDER . "/temp/")){
        // file based sessions in the local /temp/ folder. bad! oh well.
        if(_DEBUG_MODE)module_debug::log(array(
            'title' => 'Loading file based sessions',
         ));
        ini_set("session.save_handler", "files");
        session_save_path (_UCM_FOLDER . "/temp/");
    }
    session_start();
    // if there are no session values
}

if(_DEBUG_MODE)module_debug::log(array(
    'title' => 'Sessions loaded with id',
    'data' => session_id(),
 ));
if(_UCM_INSTALLED){
	if(module_config::c('database_utf8',1)){
	    // from predatorkill
	    mysql_query("SET CHARACTER SET 'utf8'");
	    mysql_query("SET NAMES 'utf8'");
	    //trying this out too
	    if(function_exists('mb_internal_encoding'))mb_internal_encoding( 'UTF-8' );
	}
}


$ucm_host = module_config::c('system_base_href','http'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '' && $_SERVER['HTTPS']!='off')?'s':'').'://'.$_SERVER['HTTP_HOST']);
// hack for ssl
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '' && $_SERVER['HTTPS']!='off'){
    $ucm_host = preg_replace('#^https?#','https',$ucm_host);
}
define('_UCM_HOST',$ucm_host);
$default_base_dir = str_replace('\\\\','\\',str_replace('//','/',dirname($_SERVER['REQUEST_URI'].'?foo=bar').'/'));
$default_base_dir = preg_replace('#includes/plugin_[^/]*/css/#','',$default_base_dir);
$default_base_dir = preg_replace('#includes/plugin_[^/]*/#','',$default_base_dir);
if(isset($external) && $external){
    // stops us saving a bogus entry in db when resetting database in demo:
    define('_BASE_HREF',module_config::c('system_base_dir'));
}else{
    define('_BASE_HREF',module_config::c('system_base_dir',$default_base_dir));
}


// a quick hack to put the re-write mode into $_REQUEST['m'] mode
if(_REWRITE_LINKS){
    $url = preg_replace('#^'.preg_quote(_BASE_HREF,'#').'#i','',$_SERVER['REQUEST_URI']);
    $url = preg_replace('#\?.*$#','',$url);
    if($url){
        $parts = explode("/",$url);
        $module_number = 0;
        foreach($parts as $part){
            if($part=='index.php')continue;
            $m = explode(".",$part);
            if(count($m) == 2){
                $_REQUEST['m'][$module_number] = $m[0];
                $_REQUEST['p'][$module_number] = $m[1];
                $module_number++;
            }
        }
    }

    define('_DEFAULT_FORM_METHOD','GET');
}else{
    define('_DEFAULT_FORM_METHOD','POST');
}


// init all our plugins.
if($plugins_to_init){
    //$uninstalled_plugins = $upgradable_plugins = array();
    foreach($plugins_to_init as $plugin_name){
        if(class_exists('module_'.$plugin_name,false)){
            eval('$plugins[$plugin_name] = new module_'.$plugin_name.'();');
            // this is a hack for php 5.2 to get the can_i() thing working
            //eval('module_'.$plugin_name.'::$module_name_hack = module_'.$plugin_name.'::get_class();');
            if(_UCM_INSTALLED && $plugins[$plugin_name]->is_plugin_enabled()){
                $plugins[$plugin_name]->init();
                //if(!$plugins[$plugin_name]->get_installed_plugin_version()){
                    //$uninstalled_plugins[$plugin_name] = &$plugins[$plugin_name];
                    //unset($plugins[$plugin_name]);
                //}
            }
        }
    }
}

hook_handle_callback('plugins_loaded');

/*foreach($plugins as $plugin_name => &$p){
    echo $plugin_name.'<br>';
    eval('echo module_'.$plugin_name.'::$module_name_hack;');
    echo '<br>';
}*/

if(!function_exists('sort_plugins')){
    function sort_plugins($a,$b){
        return $a->module_position > $b->module_position;
    }
}
uasort($plugins,'sort_plugins');


if(isset($_REQUEST['auto_login'])){
	// try to process an auto login.
	module_security::auto_login();
}
if(isset($_REQUEST['_process_reset'])){
    if(class_exists('module_captcha',false)){
        if(!module_captcha::check_captcha_form()){
            // captcha was wrong.
            _e('Sorry the captcha code you entered was incorrect. Please <a href="%s" onclick="%s">go back</a> and try again.','#','window.history.go(-1); return false;');
            exit;
        }
    }
    module_security::process_password_reset();
}
if(isset($_REQUEST['_process_login'])){
    // check recaptcha
	module_security::process_login();
}
if(isset($_REQUEST['_logout'])){
	module_security::logout();
	header("Location: index.php");
	exit;
}
if(!_UCM_INSTALLED && module_security::getcred()){
    module_security::logout();
}


// wrap the module loading request into an array
// this way we can load multiple modules and pages around eachother.
// awesome.
$inner_content = array();
$page_title_delim = ' &raquo; ';
$page_title = '';
$load_modules = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : false;
$load_pages = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : false;

if((!isset($noredirect) || !$noredirect) && !$load_modules && !$load_pages && defined('_CUSTOM_UCM_HOMEPAGE')){
    redirect_browser(_CUSTOM_UCM_HOMEPAGE);
}

if(!is_array($load_modules))$load_modules = array($load_modules);
if(!is_array($load_pages))$load_pages = array($load_pages);

if(!isset($_REQUEST['m']))$_REQUEST['m'] = array();
if(!isset($_REQUEST['p']))$_REQUEST['p'] = array();
if(!is_array($_REQUEST['m']))$_REQUEST['m'] = array($_REQUEST['m']);
if(!is_array($_REQUEST['p']))$_REQUEST['p'] = array($_REQUEST['p']);

$load_modules = array_reverse($load_modules,true);


if(_DEBUG_MODE)module_debug::log(array(
    'title' => 'Init Complete, loaded modules: ',
    'data' => implode(', ',$load_modules),
 ));