<?php
/** 
 * 
 * NOTE: Designed for use with PHP version 4, 5 and up
 * @author Santos Montano B. <ssmontano@hotmail.com, litosantosm@gmail.com>
 * @country Perú
 * @copyright 2013
 * @version: 1.0 
 * 
 */

/** 
 * Configuration file.
 */

	$C	= new stdClass;
	
	$C->INCPATH = dirname(__FILE__).'/';
	chdir( $C->INCPATH );
	
	$C->SITE_URL = 'http://www.powersbroz.com/Markethub/Application/';
	$C->DOMAIN = 'www.powersbroz.com/Markethub/Application/';

	// MySQL SETTINGS
	// 
	$C->DB_HOST = 'internal-db.s212369.gridserver.com';
	$C->DB_USER = 'db212369';
	$C->DB_PASS = 'PowersBros45me';
	$C->DB_NAME = 'db212369_markethub';
	$C->DB_MYEXT = 'mysqli'; // 'mysqli' or 'mysql'	

	
	// Folder of user data
	$C->FOLDER_DATA = "data/";
	
	// Temporary folder
	$C->FOLDER_TMP = "data/tmp/";
	
	// Avatars folder users
	$C->FOLDER_AVATAR = "data/avatars/"; 
	
	$C->AVATAR_DEFAULT = 'default.jpg';
	
	// Sizes for the avatar
	$C->widthAvatar0 = 180;
	$C->widthAvatar1 = 100;
	$C->heightAvatar1 = 100;
	$C->widthAvatar2 = 50;
	$C->heightAvatar2 = 50;
	$C->widthAvatar3 = 26;
	$C->heightAvatar3 = 26;	
	
	$C->SIZE_IMAGEN_AVATAR = 500000;
	


	// photos folder users
	$C->FOLDER_PHOTOS = "data/photos/";
	
	$C->SIZE_PHOTO = 2000000;
	
	// Sizes for the photos
	$C->widthPhoto0 = 730;
	$C->widthPhoto1 = 150;
	$C->widthPhoto2 = 100;
	$C->widthPhoto3 = 50;

	
	//if you want to view page view statistics in administration section set the value to TRUE,
	$C->write_page_view_is_active	= FALSE;
	
	$C->DEBUG_USERS		= array();
	
	$C->DEBUG_MODE = in_array($_SERVER['REMOTE_ADDR'], $C->DEBUG_USERS);
	
	if( $C->DEBUG_MODE ) {
		ini_set( 'error_reporting', E_ALL | E_STRICT	);
		ini_set( 'display_errors',			1	);
	}


?>