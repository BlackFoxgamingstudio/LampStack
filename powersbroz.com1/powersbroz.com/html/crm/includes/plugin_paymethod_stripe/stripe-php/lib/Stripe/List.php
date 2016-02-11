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

class Stripe_List extends Stripe_Object
{
  public static function constructFrom($values, $apiKey=null)
  {
    $class = get_class();
    return self::scopedConstructFrom($class, $values, $apiKey);
  }

  public function all($params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    list($response, $apiKey) = $requestor->request('get', $this['url'], $params);
    return Stripe_Util::convertToStripeObject($response, $apiKey);
  }

  public function create($params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    list($response, $apiKey) = $requestor->request('post', $this['url'], $params);
    return Stripe_Util::convertToStripeObject($response, $apiKey);
  }

  public function retrieve($id, $params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    $base = $this['url'];
    $id = Stripe_ApiRequestor::utf8($id);
    $extn = urlencode($id);
    list($response, $apiKey) = $requestor->request('get', "$base/$extn", $params);
    return Stripe_Util::convertToStripeObject($response, $apiKey);
  }

}
