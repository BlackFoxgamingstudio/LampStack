<?php

/**
 * videoeditor action
 *
 * login
 */

if( !isset( $config ) ) exit;

$errors = array();
$is_db_auth = !empty( $config['db']['user'] ) && !empty( $config['db']['db_name'] );//Users from Database
$field_username = isset( $_POST['username'] ) ? addslashes( trim( $_POST['username'] ) ) : '';
$field_password = isset( $_POST['password'] ) ? addslashes( trim( $_POST['password'] ) ) : '';

if( $field_username && $field_password ){
    
    //users from DB
    if( $is_db_auth ){
        
        $db = mysqli_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'] ) or die("MySQL Error " . mysqli_error( $connect ) ); 
        $sql = "SELECT * FROM `{$config['db']['users_table']}` WHERE (`username` = '{$field_username}' OR `email` = '{$field_username}') AND `password` = '" . md5($field_password) . "'"; 
        
        if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
        
        if( !$result = $db->query( $sql ) ){
            die( 'There was an error running the query [' . $db->error . ']' );
        }
        
        if( $result->num_rows > 0 ){
            
            while( $row = $result->fetch_assoc() ){
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = $row['type'] == 'admin';
                
                header("Location: " . str_replace('auth.php','',$_SERVER['PHP_SELF']) );
                exit;
                
            }
            
        }
        
        $errors[] = LANG_FAIL_LOGIN;
        
    }
    //users from config file
    else{
        
        foreach( $config['users'] as $user ){
            
            if( $user['username'] === $field_username ){
                
                if( $user['password'] === $field_password ){
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['is_admin'] = $user['type'] == 'admin';
                    
                    header("Location: " . str_replace('auth.php','',$_SERVER['PHP_SELF']) );
                    exit;
                    
                }
                
                break;
            }
            
        }
        
        $errors[] = LANG_FAIL_LOGIN;
        
    }
    
}
