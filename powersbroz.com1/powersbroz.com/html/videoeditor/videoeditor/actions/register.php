<?php

/**
 * videoeditor action
 *
 * register
 */

if( !isset( $config ) ) exit;

$errors = array();
$is_db_auth = !empty( $config['db']['user'] ) && !empty( $config['db']['db_name'] );//Users from Database
$field_username = isset( $_POST['username'] ) ? addslashes( trim( $_POST['username'] ) ) : '';
$field_email = isset( $_POST['email'] ) ? addslashes( trim( $_POST['email'] ) ) : '';
$field_password = isset( $_POST['password'] ) ? addslashes( trim( $_POST['password'] ) ) : '';
$field_password_re = isset( $_POST['password_re'] ) ? addslashes( trim( $_POST['password_re'] ) ) : '';

if( !$is_db_auth ){
    header("Location: " . $_SERVER['PHP_SELF'] );
    exit;
}

if( $field_username && $field_email && $field_password && $field_password_re ){
    
    if( strpos( $field_email, '@' ) === false ){
        $errors[] = LANG_NOTVALID_EMAIL;
    }
    if( $field_password != $field_password_re ){
        $errors[] = LANG_NOTVALID_PASSWORD;
    }
    
    if( empty( $errors ) ){
        
        //check username
        $db = mysqli_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'] ) or die("MySQL Error " . mysqli_error( $connect ) ); 
        $sql = "SELECT * FROM `{$config['db']['users_table']}` WHERE `username` = '{$field_username}'"; 
        
        if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
        
        if( !$result = $db->query( $sql ) ){
            die( 'There was an error running the query [' . $db->error . ']' );
        }
        if( $result->num_rows > 0 ){
            $errors[] = LANG_NOTVALID_USERNAME;
        }
        
        //check email
        $sql = "SELECT * FROM `{$config['db']['users_table']}` WHERE `email` = '{$field_email}'"; 
        
        if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
        
        if( !$result = $db->query( $sql ) ){
            die( 'There was an error running the query [' . $db->error . ']' );
        }
        if( $result->num_rows > 0 ){
            $errors[] = LANG_NOTVALID_EMAIL_USED;
        }
        
    }
    
    //register user
    if( empty( $errors ) ){
        
        $field_password_md5 = md5( $field_password );
        $sql = "INSERT INTO `{$config['db']['users_table']}` (`username`, `email`, `password`, `type`) VALUES ('{$field_username}', '{$field_email}', '{$field_password_md5}', 'user');"; 
        
        if( !$result = $db->query( $sql ) ){
            if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
            die( 'There was an error running the query [' . $db->error . ']' );
        }else{
            $_SESSION['sessage'] = LANG_REGISTER_SUCCESS;
            header("Location: " . $_SERVER['PHP_SELF'] . '?act=' . $action );
            exit;
        }
        
    }
    
}

