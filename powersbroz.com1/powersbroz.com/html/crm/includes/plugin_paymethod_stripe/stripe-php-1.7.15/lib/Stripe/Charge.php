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

class Stripe_Charge extends Stripe_ApiResource
{
  public static function constructFrom($values, $apiKey=null)
  {
    $class = get_class();
    return self::scopedConstructFrom($class, $values, $apiKey);
  }

  public static function retrieve($id, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedRetrieve($class, $id, $apiKey);
  }

  public static function all($params=null, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedAll($class, $params, $apiKey);
  }

  public static function create($params=null, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedCreate($class, $params, $apiKey);
  }
    
  public function save()
  {
    $class = get_class();
    return self::_scopedSave($class);
  }

  public function refund($params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    $url = $this->instanceUrl() . '/refund';
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    $this->refreshFrom($response, $apiKey);
    return $this;
  }

  public function capture($params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    $url = $this->instanceUrl() . '/capture';
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    $this->refreshFrom($response, $apiKey);
    return $this;
  }

  public function updateDispute($params=null)
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    $url = $this->instanceUrl() . '/dispute';
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    $this->refreshFrom(array('dispute' => $response), $apiKey, true);
    return $this->dispute;
  }
}