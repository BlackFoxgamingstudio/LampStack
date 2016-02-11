<?php

/**
 * videoeditor action
 *
 * recovery
 */

if( !isset( $config ) ) exit;

$errors = array();
$is_db_auth = !empty( $config['db']['user'] ) && !empty( $config['db']['db_name'] );//Users from Database

$field_username = isset( $_POST['username'] ) ? addslashes( trim( $_POST['username'] ) ) : '';
$accept_to = isset( $_GET['accept'] ) && is_numeric( $_GET['accept'] ) ? $_GET['accept'] : '';
$secret_code = isset( $_GET['code'] ) ? $_GET['code'] : '';

if( !$is_db_auth ){
    header("Location: " . $_SERVER['PHP_SELF'] );
    exit;
}

if( !function_exists( 'generatePassword' ) ){
    function generatePassword( $length = 8, array $options = array() ) {
        $options = array_merge(array(
            'allowable_characters' => 'abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789',
            'srand_seed_multiplier' => 1000000,
        ),$options);
        $ps_len = strlen($options['allowable_characters']);
        srand((double) microtime() * $options['srand_seed_multiplier']);
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $options['allowable_characters'][mt_rand(0, $ps_len -1)];
        }
        return $pass;
    }
}

if( $field_username ){
    
    $db = mysqli_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'] ) or die("MySQL Error " . mysqli_error( $connect ) ); 
    $sql = "SELECT * FROM `{$config['db']['users_table']}` WHERE `username` = '{$field_username}' OR `email` = '{$field_username}'"; 
    
    if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
    
    if( !$result = $db->query( $sql ) ){
        die( 'There was an error running the query [' . $db->error . ']' );
    }
    
    if( $result->num_rows > 0 ){
        
        $user = $row = $result->fetch_assoc();
        
        $new_password = generatePassword();
        
        $sql = "UPDATE `{$config['db']['users_table']}` SET `new_password` = '" . md5( $new_password ) . "' WHERE `id` = '" . $user['id'] . "'";
        
        if( !$result = $db->query( $sql ) ){
            if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
            die( 'There was an error running the query [' . $db->error . ']' );
        }else{
            
            //send email
            require_once $root_path . '/actions/phpmailer/class.phpmailer.php';
            
            $accept_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?act=recovery&accept=' . $user['id'] . '&code=' . $config['secret_code'] . $user['id'];
            $mail_body = file_get_contents( dirname(dirname(__FILE__)) .  '/languages/' . $config['lang'] . '_mail_password_recovery.html' );
            $mail_body = str_replace( array('{new_password}','{accept_link}'), array($new_password,$accept_link), $mail_body );
            
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom( $config['mail']['email_from'], $config['mail']['email_from_name'] );
            $mail->Subject = LANG_RECOVERY;
            $mail->AddAddress( $user['email'] );
            $mail->MsgHTML( $mail_body );
            $mail->AltBody = strip_tags( $mail_body . ' ' . $accept_link );
            
            if( !empty( $config['mail']['smtp_host'] ) && !empty( $config['mail']['smtp_username'] ) ){
                
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                if( !empty( $config['mail']['smtp_secure'] ) ) $mail->SMTPSecure = $config['mail']['smtp_secure'];
                $mail->Host = $config['mail']['smtp_host'];
                $mail->Port = $config['mail']['smtp_port'];
                $mail->Username = $config['mail']['smtp_username'];
                $mail->Password = $config['mail']['smtp_password'];
                
            }else{
                //$mail->IsSendmail();
            }
            
            if(!$mail->Send()) {
                
                echo "Mailer Error: " . $mail->ErrorInfo;
                exit;
                
            } else {
                
                $_SESSION['sessage'] = LANG_RECOVERY_SUCCESS;
                header("Location: " . $_SERVER['PHP_SELF'] . '?act=' . $action );
                exit;
                
            }
            
        }
        
    }else{
        
        $errors[] = LANG_USERNAME_NOTFOUND;
        
    }
    
}

//accept new password
else if( $accept_to && $secret_code ){
    
    $db = mysqli_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'] ) or die("MySQL Error " . mysqli_error( $connect ) ); 
    $sql = "SELECT * FROM `{$config['db']['users_table']}` WHERE `id` = '{$accept_to}'"; 
    
    if( !$result = $db->query( $sql ) ){
        if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
        die( 'There was an error running the query [' . $db->error . ']' );
    }else{
        
        $user = $row = $result->fetch_assoc();
        
        if( !empty( $user['new_password'] ) && $config['secret_code'] . $user['id'] === $secret_code ){
            
            $sql = "UPDATE `{$config['db']['users_table']}` SET `password` = '" . $user['new_password'] . "', `new_password` = '' WHERE `id` = '" . $user['id'] . "'";
            
            if( !$result = $db->query( $sql ) ){
                if( !empty( $config['debug'] ) ){ echo '<br><pre>' . $sql . '</pre>'; }//debug SQL
                die( 'There was an error running the query [' . $db->error . ']' );
            }else{
                
                $_SESSION['sessage'] = LANG_RECOVERY_ACCEPTED;
                header("Location: " . $_SERVER['PHP_SELF'] );
                exit;
                
            }
            
        }
        
    }
    
}
