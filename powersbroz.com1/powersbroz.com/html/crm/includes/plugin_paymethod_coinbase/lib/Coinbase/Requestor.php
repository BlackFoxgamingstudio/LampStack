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

class Coinbase_Requestor
{

    public function doCurlRequest($curl)
    {
        $response = curl_exec($curl);

        // Check for errors
        if($response === false) {
            $error = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            throw new Coinbase_ConnectionException("Network error " . $message . " (" . $error . ")");
        }

        // Check status code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($statusCode != 200) {
            throw new Coinbase_ApiException("Status code " . $statusCode, $statusCode, $response);
        }

        return array( "statusCode" => $statusCode, "body" => $response );
    }

}