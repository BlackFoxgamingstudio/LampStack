<?php

//Ultimate Client Manager - config file

define('_DB_SERVER','external-db.s212369.gridserver.com');
define('_DB_NAME','db212369_NexusHub');
define('_DB_USER','db212369');
define('_DB_PASS','PowersBros45me');
define('_DB_PREFIX','ucm_');

define('_UCM_VERSION',2);
define('_UCM_FOLDER',preg_replace('#includes$#','',dirname(__FILE__)));
define('_UCM_SECRET','2ab59dc626d0106eb93993892c6f08d9'); // change this to something unique

define('_EXTERNAL_TUNNEL','ext.php');
define('_EXTERNAL_TUNNEL_REWRITE','external/');
define('_ENABLE_CACHE',true);
define('_DEBUG_MODE',false);
define('_DEMO_MODE',false);
if(!defined('_REWRITE_LINKS'))define('_REWRITE_LINKS',false);

ini_set('display_errors',false);
ini_set('error_reporting',0);

