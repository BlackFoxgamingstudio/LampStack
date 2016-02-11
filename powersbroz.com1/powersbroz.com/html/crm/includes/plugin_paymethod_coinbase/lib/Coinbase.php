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

if(!function_exists('curl_init')) {
    throw new Exception('The Coinbase client library requires the CURL PHP extension.');
}

require_once(dirname(__FILE__) . '/Coinbase/Exception.php');
require_once(dirname(__FILE__) . '/Coinbase/ApiException.php');
require_once(dirname(__FILE__) . '/Coinbase/ConnectionException.php');
require_once(dirname(__FILE__) . '/Coinbase/Coinbase.php');
require_once(dirname(__FILE__) . '/Coinbase/Requestor.php');
require_once(dirname(__FILE__) . '/Coinbase/Rpc.php');
require_once(dirname(__FILE__) . '/Coinbase/OAuth.php');
require_once(dirname(__FILE__) . '/Coinbase/TokensExpiredException.php');
require_once(dirname(__FILE__) . '/Coinbase/Authentication.php');
require_once(dirname(__FILE__) . '/Coinbase/SimpleApiKeyAuthentication.php');
require_once(dirname(__FILE__) . '/Coinbase/OAuthAuthentication.php');
require_once(dirname(__FILE__) . '/Coinbase/ApiKeyAuthentication.php');
