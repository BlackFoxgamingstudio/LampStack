<?php

// test for existing files or folders. this should be in an empty directory!
// test we have correct php version (5.3) complain on 5.2, fail on below.
// test we can create files
// test we can create folders
// test we can get data with curl
// test we have all available functions (eg: curl, imap)
// test we can unzip files.
// create temp folder
// ask for users licence code(s)
// post these to server, getting back a zip archive with latest system as per licence codes
// save zip archive in temp folder
// extract zip archive in current directory

$this_folder_on_server = dirname(__FILE__).DIRECTORY_SEPARATOR;

$errors = array();

// check existing files.
if(is_file($this_folder_on_server.'index.php')||is_dir($this_folder_on_server.'includes')){
    die("Sorry, please run this install.php file from an empty directory (ie: no existing UCM installation with an index.php file or includes/ folder). For support please send in a support ticket: http://ultimateclientmanager.com/support/support-ticket/");
}

$temp_folder = $this_folder_on_server . "temp/";
if(!is_dir($temp_folder) || !is_writable($temp_folder)){
    @mkdir($temp_folder);
}
if(!is_dir($temp_folder) || !is_writable($temp_folder)){
    // try to create it.
    $errors[]='Sorry, the folder <strong>'.$this_folder_on_server.'</strong> is not writable by PHP. Please contact the hosting provider and ask for this folder to be set writable by PHP. Or change the permissions to writable using your FTP program.';
}
$required_php_version = '5.3.0';
if(version_compare(PHP_VERSION, $required_php_version) < 0){
    $setup_errors=true;
    $errors[]=("I'm sorry, a PHP version of $required_php_version or above is REQUIRED to run this - the current PHP version is: ".PHP_VERSION . ". The web hosting provider can usually push a button to upgrade, please contact them");
}
/*
$required_php_version = '5.3.0';
if(version_compare(PHP_VERSION, $required_php_version) < 0){
    $setup_errors=true;
    $errors[]=("PHP version $required_php_version or above RECOMMENDED to run this program - the current PHP version is: ".PHP_VERSION . ". The web hosting provider can usually push a button to upgrade, please contact them. You can still try to install this by clicking the Ignore Errors button.");
}*/

function ucm_install_return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
$memory_limit = ini_get('memory_limit');
if(!$memory_limit || ucm_install_return_bytes($memory_limit) < 67108864){
    // try to increase to 64M
    @ini_set('memory_limit','64M');
    $memory_limit = ini_get('memory_limit');
    if(!$memory_limit || ucm_install_return_bytes($memory_limit) < 67108864){
        $errors[] = "This system has a PHP memory limit of '$memory_limit'. Some things may fail (like generating a PDF). Please ask hosting provider to increase this to at least 64M";
    }
}


if(!class_exists('SimpleXMLElement',false)){
    $setup_errors = true;
    $errors[]=('Sorry SimpleXMLElement is not enabled on your server. Please enable this before continuing.');
}else{
    $xml = '<foo><bar id="f"><moo>123</moo></bar><cat /></foo>';
    $foo = new SimpleXMLElement($xml);
    if(!$foo || $foo->bar->moo != 123){
        $setup_errors = true;
        $errors[]=('Error with SimpleXMLElement class. Please check it is enabled on your hosting account');
    }
}

if(!function_exists('imap_open')){
    $setup_errors=true;
    $errors[]=('Sorry, the PHP IMAP extension is not enabled on your hosting account. Some features like newsletter bounce tracking will not work. Please contact the hosting provider to have this enabled.');
}
if(!function_exists('curl_init')){
    $setup_errors=true;
    $errors[]=('Sorry CURL is not enabled on your hosting account. Please contact your host to have this enabled.');
}else{
    // do a test connection
    $ch = curl_init('http://api.ultimateclientmanager.com/?curl_check');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER,false);
    $result = curl_exec($ch);
    if(trim($result) != 'success'){
        $setup_errors=true;
        $errors[]=('There was a problem with CURL. Please check CURL is enabled and your server has a connection to the internet.');
    }else{
    }
}


/* from php.net. simply unzip function */
function unzip($file,$debug=false,$die=true,$overwrite=false){

    if(class_exists('ZipArchive')){
        $zip = new ZipArchive;
        $zip->open($file);
        $zip->extractTo('.');
        $zip->close();
        if(is_file('index.php')){
            return true;
        }else{
            // zip archive failed, trying seomthing new.
        }
    }
    if(!function_exists('zip_open')){
        $result = 0;
        passthru("unzip $file",$result);
        if(!$result)return true;
        else return false;
    }
    $zip = zip_open($file);
    if(is_resource($zip)){
        while(($zip_entry = zip_read($zip)) !== false){
            if($debug)echo "Unpacking ".zip_entry_name($zip_entry)."\n";
            if(strpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR) !== false){
                $last = strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR);
                $dir = substr(zip_entry_name($zip_entry), 0, $last);
                $file = substr(zip_entry_name($zip_entry), strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR)+1);
                if(!is_dir($dir)){
                    if(!mkdir($dir, 0755, true)){
                        if($die)die("Unzipping Failed: Unable to create directory: $dir\n");
                        else return false;
                    }
                }
                if(strlen(trim($file)) > 0){
                    if(!file_exists($dir."/".$file) || $overwrite){
                        $return = @file_put_contents($dir."/".$file, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                    }else{
                        if($debug)echo "File already exists: ".$dir."/".$file."\n";
                        $return = false;
                    }
                    if($return === false){
                        if($die)die("Unzipping Failed: Unable to write file: $dir/$file\n");
                        else continue;
                    }
                }
            }else{
                $file = zip_entry_name($zip_entry);
                if(!file_exists($file) || $overwrite){
                    $return = @file_put_contents($file, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                }else{
                    if($debug)echo "File already exists: ".$file."\n";
                    $return = false;
                }
                if($return === false){
                    if($die)die("Unzipping Failed: Unable to write file: $file\n");
                    else  continue;
                }
            }
        }
    }else{
        if($die)die("Unzipping Failed: Unable to open zip file: $file \n");
        else return false;
    }
    return true;
}


if(function_exists('curl_init')){
    // do a test connection
    $ch = curl_init('http://api.ultimateclientmanager.com/?zip_check');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER,false);
    $result = curl_exec($ch);
    if($result){
        file_put_contents('temp/test.zip',$result);
        chdir("temp/");
        if(!unzip('test.zip',false,false)){
            $errors[] = "Unzip failed. Please try the alternative installer.";
        }else{
            // check if files exist
            if(is_file('testfile.txt') && is_dir('testdir') && is_file('testdir/testfile.txt') && strlen(file_get_contents('testfile.txt')) > 5 && strlen(file_get_contents('testdir/testfile.txt')) > 5){

            }else{
                $error[] = "Unziping files failed. Please try the alternative installer.";
            }
            @unlink('testfile.txt');
            @unlink('testdir/testfile.txt');
            @rmdir('testdir');
            @unlink('test.zip');
        }
        chdir("../");

    }
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>UCM - Installer</title>
    <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/blue.css" type="text/css" />
    <!--[if IE 9]> <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/styles_ie9.css" type="text/css" /> <![endif]-->
    <!--[if IE 8]> <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/styles_ie8.css" type="text/css" /> <![endif]-->
    <!--[if IE 7]> <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/styles_ie7.css" type="text/css" /> <![endif]-->

    <link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="http://ultimateclientmanager.com/files/lite-edition/css/jquery.lightbox-0.5.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>

<div id="home_page"></div>
<div id="top_header">
    <div id="header">
        <div id="logo" class="logot"><a href="/" style="background: url(http://ultimateclientmanager.com/images/logo_ucm.png) no-repeat center;">UCM - Installer</a></div>
        <div id="menu">
        </div>
    </div>
</div>
<!--end header-->
<div id="bg_top" style="padding: 0;">
    <div id="wrapper">

        <!--contact box-->
        <div class="main_box_wrapper_nopadding" id="support">
            <div class="main_box">
                <div class="content">
                    <h1>Welcome to the UCM Installer</h1>

                    <?php


                    if(isset($_REQUEST['do_install']) && isset($_REQUEST['licence_code']) && strlen($_REQUEST['licence_code'])>10){

                        // grab a zip file from our server and try to unzip it.
                        // copied from init.php
                        $ucm_host = 'http'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '' && $_SERVER['HTTPS']!='off')?'s':'').'://'.$_SERVER['HTTP_HOST'];
                        $default_base_dir = str_replace('\\\\','\\',str_replace('//','/',dirname($_SERVER['REQUEST_URI'].'?foo=bar').'/'));
                        $default_base_dir = preg_replace('#includes/plugin_[^/]*/css/#','',$default_base_dir);
                        $default_base_dir = preg_replace('#includes/plugin_[^/]*/#','',$default_base_dir);

                        //$default_base_dir = str_replace('\\\\','\\',str_replace('//','/',dirname($_SERVER['REQUEST_URI'].'?foo=bar').'/'));

                        $post = array(
                            'installation_code' => $_REQUEST['licence_code'],
                            'client_ip' => $_SERVER['REMOTE_ADDR'],
                            'current_version' => 0,
                            'installation_location' => $ucm_host.$default_base_dir,
                            'get_file_contents' => 1,
                            'return_as_zip' => 1,
                            'return_base_system_in_zip' => 1,
                            'email_address' => isset($_REQUEST['email_address']) ? $_REQUEST['email_address'] : '',
                            'newsletter_signup' => isset($_REQUEST['newsletter_signup']) ? $_REQUEST['newsletter_signup'] : '0',
                        );

                        /*$ch = curl_init('http://d2dn5ew9duvxlk.cloudfront.net/api/upgrade.php?'.http_build_query($post));
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($ch,CURLOPT_HEADER,false);
                        //curl_setopt($ch,CURLOPT_POST,true);
                        //curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
                        $result = curl_exec($ch);*/

                        $ch = curl_init('http://api.ultimateclientmanager.com/upgrade.php');
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($ch,CURLOPT_HEADER,false);
                        curl_setopt($ch,CURLOPT_POST,true);
                        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
                        $result = curl_exec($ch);
                        if(strlen($result)>100 && strpos($result,'PK')===0){
                            file_put_contents('temp/system.zip',$result);
                            ?>
                            <h2>Latest version of UCM received, unzipping files...</h2>
                            <pre style="font-size: 10px; max-height: 500px; overflow-y: auto; line-height: 9px;"><?php
                            $unzip_status = unzip('temp/system.zip',true,false);
                            ?></pre>
                            <?php if($unzip_status){ ?>
                                <p>
                                Success! It looks like all the files have been copied successfully! Wasn't that easy?<br/>
                                Please <a href="index.php">click here</a> to continue the UCM installation process.
                            <?php }else{ ?>
                                Oh oh! Looks like we had a problem unzipping some files. Please look through the above list for any error messages. Please send these error messages along with FTP details as a support ticket and we can assist installing this sytem asap for you.
                            <?php }

                            @unlink('temp/system.zip');
                        }else{
                            echo "<p>Temporary Error With License Code. Please click back and try again.</p>";
                            $message = @json_decode($result,true);
                            if($message && isset($message['available_updates']) && isset($message['available_updates']['message'])){
                                echo $message['available_updates']['message'];
                            }
                        }

                    }else if(count($errors) && !isset($_REQUEST['ignore_errors'])){
                        ?>
                        <h2>Sorry, we've found some possible problems:</h2>
                            <ul>
                                <?php foreach($errors as $error){ ?>
                                    <li><?php echo $error;?></li>
                                <?php } ?>
                            </ul>
                            <p>Please fix these errors before continuing.</p>
                            <ul class="buttons_float">
                                <li style="margin-right: 43px;">
                                    <div class="button_med"><a href="?tryagain">check again</a></div>
                                </li>
                                <li>
                                    <div class="button_med"><a href="?ignore_errors">ignore errors</a></div>
                                </li>
                            </ul>
                        <?php
                    }else{
                        ?>
                        <h2>Great news! All system are go, ready for installation!</h2>

                        <p>Now it's time to automatically install the latest version of Ultimate Client Manager. <br/>
                            You will need to create a MySQL database (hosting provider can assist with this) and have the username/password ready for the next step.
                            </p>
                        <p>
                            To proceed please enter the <strong>license purchase code</strong> below.<br/>
                            This code is available in the license file after purchasing, please see the downloads page on CodeCanyon.net (<a href="http://dtbaker.net/admin/includes/plugin_envato/images/envato-license-code.gif" target="_blank">click here for help</a>). <br/>
                            The license code will look something like this: 30d91230-a8df-4545-1237-467abcd5b920
                        </p>

                        <div style="padding:10px;">
                            <form action="?do_install" method="post">

                                <h3>Please enter the UCM license purchase code:</h3>
                                <div style="padding-bottom: 10px">
                                    <input type="text" name="licence_code" value="" style="width:400px; padding:5px; border:1px solid #CCC;">
                                </div>
                                <h3>Please enter your email address (completely optional):</h3>
                                <div>
                                    <input type="text" name="email_address" value="" style="width:400px; padding:5px; border:1px solid #CCC;">
                                    <br/>
                                    <input type="checkbox" name="newsletter_signup" value="1" checked> Signup for the UCM newsletter so you know when new features are released. <a href="http://ultimateclientmanager.com/newsletters/" target="_blank">Click here</a> to see past newsletters.
                                </div>

                                <br/>

                                <input type="submit" name="go" value="Install UCM" class="submit_button" onclick="this.value='Installing files... this may take a few minutes.'">

                            </form>
                        </div>



                        <?php
                    }
                    ?>



                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>If you require support, or assistance installing this item, please send in a support ticket here: <a href="http://ultimateclientmanager.com/support/support-ticket/" target="_blank">http://ultimateclientmanager.com/support/support-ticket/</a> </p>

                    <div class="clear"></div>
                </div>
            </div>
            <div class="shadow"></div>
        </div>
        <!--end contact box-->
        <div class="spacer_graphic6"></div>

    </div>
</div>
<!--end wrapper-->
<!--bg top-->

</body></html>

