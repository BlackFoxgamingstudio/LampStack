<?php
/**
 * @package Entity Application Configuration File
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/
 *
 */

/** ====================================================
 * DEPLOYMENT SETTINGS
 * Important application settings to let the application
 * know where it is and how to load resources
 */


// ENVIRONMENT VARIABLE
// "local" or "live" used for development on local server
// and deployment to your website

define('ENVIRONMENT',   'deployment');

define('OFFLINE',       false);

// DO NOT EDIT THESE VARIABLES!!!
define("DEMO",          false);

define("DEBUG",         false);

define("APPROPRIATE",   (!DEMO && ENVIRONMENT != 'local'));

define("DEMO_MSG",      'Feature disabled in demo');

/** ====================================================
 * SYSTEM SETTINGS
 * Customizable user settings responsible for global
 * behavior
 */

// Timezone settings
// For a list of supported time zones visit: http://php.net/manual/en/timezones.php
define('TIME_ZONE', 'America/Los_Angeles');
$timezone = date_default_timezone_set(TIME_ZONE); // DO NOT EDIT



/** =================================================================================================
 * DATABASE CONNECTION SETTINGS
 * DB_HOST is your server's http hostname. You can leave it "localhost" in almost all cases.
 * DB_USERNAME is the username you use to access the database created for ZPD
 * DB_PASS is the password for the above user
 * DB_NAME the name of the database that you created for ZPD
 */

    if (ENVIRONMENT == 'deployment') {

        // These are the local settings for local testing

        define("DB_HOST",       "internal-db.s212369.gridserver.com");
        define("DB_USERNAME",   "db212369");
        define("DB_PASS", 		"PowersBros45me");
        define("DB_NAME",   	"db212369_Pmanager");

        error_reporting(E_ALL);

    } else {

        // These are the live settings for your live site

        define("DB_HOST",   	"localhost");   // Leave localhost
        define("DB_USERNAME",	"");            // Your Database username
        define("DB_PASS",   	"");            // Your Database Password
        define("DB_NAME",   	"");            // Name of your database

        error_reporting(0);

    }

/** ====================================================
 * DIRECTORY SETTINGS
 * Important application settings to let the application
 * know where it is and how to load resources
 */


    // URL CONFIGURATION SETTINGS
    // BASE_URL is the http address of the Website i.e. http://www.yoursite.com/
    // ROOT_URL is relative to the public_html folder
    // DO NOT FORGET TO LEAVE THE TRAILING SLASH IN BOTH!

    if (ENVIRONMENT == "deployment") {

        define("BASE_URL",  "http://powersbroz.com/pmanager/");
        define("ROOT_URL",  "/pmanager/");

    } else {

        define("BASE_URL",  "http://www.yoursite.com/entity/");
        define("ROOT_URL",  "/entity/");

    }

    // Do not edit website code unless you have a specific reason to do so
    // such as in the case of subdomains or something else advanced you have
    // configured your server to deal with
    define("WEBSITE",   str_replace(ROOT_URL, "", BASE_URL));

    // COMPILED INCLUDE URLS (DO NOT EDIT)

    define("AVATARS",           BASE_URL . "img/avatars/");

    define("DATA_COLLECTIONS",  BASE_URL . 'app/collections/');

    define("FILES_URL",         BASE_URL . 'files/');

    define("GROUP_IMAGES",      BASE_URL . 'img/groups/');

    define("IMAGES",            BASE_URL . 'img/');

    define("PROJECT_IMAGES",    BASE_URL . 'img/projects/');

    define("VIEW_COLLECTIONS",  BASE_URL . 'app/views/collections/');

    // FILE SYSTEM PATHS (DO NOT EDIT)

    define("ROOT_PATH",             $_SERVER['DOCUMENT_ROOT'] . ROOT_URL);

    define("ACTIONS",               ROOT_PATH . 'app/actions/');

    define("AVATARS_PATH",          ROOT_PATH . 'img/avatars/');

    define("CLASSES",               ROOT_PATH . 'app/classes/');

    define("FILES_PATH",            ROOT_PATH . 'files/');

    define("FORM_FILES_PATH",       ROOT_PATH . 'files/forms/');

    define("GROUP_IMAGES_PATH",     ROOT_PATH . 'img/groups/');

    define("INCLUDES",              ROOT_PATH . 'app/inc/');

    define("LOGS",                  ROOT_PATH . 'logs/');

    define("PROJECT_IMAGES_PATH",   ROOT_PATH . 'img/projects/');

    define("VIEWS",                 ROOT_PATH . 'app/views/');