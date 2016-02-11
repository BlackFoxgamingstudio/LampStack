<?php

//ini_set('display_errors',1);
//error_reporting(E_ALL);

session_start();

if( !defined( 'REQUIRE_AUTH' ) ){
    define( 'REQUIRE_AUTH', true );
}

//load config
$root_path = dirname( __FILE__ );
if( !isset( $config ) ) {
    require $root_path . '/config.php';
}
$output = '';
$message_error = '';
$message_info = '';
if( isset( $_SESSION['sessage'] ) && !is_array( $_SESSION['sessage'] ) ){
    $message_info = $_SESSION['sessage'];
    $_SESSION['sessage'] = NULL;
    unset($_SESSION['sessage']);
}
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

//debug mode
if( !empty( $config['debug'] ) ){
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
}

//load language file
if( file_exists( $root_path . '/languages/' . $config['lang'] . '.php' ) ){
    $lang_file = $root_path . '/languages/' . $config['lang'] . '.php';
}else{
    $lang_file = $root_path . '/languages/en.php';
}
require $lang_file;

//authorization
if( REQUIRE_AUTH ){
    if( empty( $_SESSION['user_id'] ) ){
        exit;
    }else{
        $config['user_id'] = $_SESSION['user_id'];
        $config['is_admin'] = $_SESSION['is_admin'];
        $config['access_permissions'] = $config['users_access_permissions'][($config['is_admin'] ? 'admin' : 'user')];
    }
}

//action name
if( !isset( $action ) ) {
    $action = !empty( $_POST['action'] ) && !is_array( $_POST['action'] ) ? trim( $_POST['action'] ) : ( !empty( $_GET['act'] ) ? $_GET['act'] : '' );
}

//include action script
if( !empty( $action ) && file_exists( $root_path . '/actions/' . $action . '.php' ) ){
    
    require $root_path . '/actions/' . $action . '.php';
    
    if( !empty( $errors ) && is_array( $errors ) ){
        $message_error = implode( '; ', $errors ) . '.';
    }
    
}

//call action method
else if( !empty( $action ) ) {
    
    //load class
    require_once $root_path . '/videoeditor.class.php';
    $videoEditor = new videoEditor( $config );
    
    //call method
    if( method_exists( $videoEditor, 'action_' . $action ) ){
        
        $output = array( 'data' => array(), 'msg' => '' );
        $options = array();
        
        call_user_func( array( &$videoEditor, 'action_' . $action ), $options );
        
        $output = $videoEditor->output;
        $output = defined('JSON_UNESCAPED_SLASHES') ? json_encode( $output, JSON_UNESCAPED_SLASHES ) : json_encode( $output );
        
    }
    
}

echo $output;
