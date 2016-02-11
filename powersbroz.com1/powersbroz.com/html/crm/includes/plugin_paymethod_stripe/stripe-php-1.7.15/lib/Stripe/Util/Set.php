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

class Stripe_Util_Set
{
  private $_elts;

  public function __construct($members=array())
  {
    $this->_elts = array();
    foreach ($members as $item)
      $this->_elts[$item] = true;
  }

  public function includes($elt)
  {
    return isset($this->_elts[$elt]);
  }

  public function add($elt)
  {
    $this->_elts[$elt] = true;
  }

  public function discard($elt)
  {
    unset($this->_elts[$elt]);
  }

  // TODO: make Set support foreach
  public function toArray()
  {
    return array_keys($this->_elts);
  }
}
