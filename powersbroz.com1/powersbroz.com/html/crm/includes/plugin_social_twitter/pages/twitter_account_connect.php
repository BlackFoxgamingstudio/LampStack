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
if(!module_social::can_i('edit','Twitter','Social','social')){
    die('No access to Twitter accounts');
}

$social_twitter_id = isset($_REQUEST['social_twitter_id']) ? (int)$_REQUEST['social_twitter_id'] : 0;
$twitter_account = new ucm_twitter_account($social_twitter_id);
if($twitter_account->get('social_twitter_id') && $twitter_account->get('social_twitter_id') == $social_twitter_id){


	// do a quick oauth.

	require_once 'includes/plugin_social_twitter/includes/tmhOAuth.php';


	class dtbaker_tmhOAuth extends tmhOAuth {
	  public function __construct($config = array()) {

	    $this->config = array_merge(
	      array(

	        // change the values below to ones for your application
	        'consumer_key'    => module_config::c('social_twitter_api_key',''),
	        'consumer_secret' => module_config::c('social_twitter_api_secret',''),
	        'user_agent'      => 'UCM Twitter 0.1',
	      ),
	      $config
	    );
	    parent::__construct($this->config);
	  }
  }

$tmhOAuth = new dtbaker_tmhOAuth();

function php_self($dropqs=true) {
  $protocol = 'http';
  if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
    $protocol = 'https';
  } elseif (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) {
    $protocol = 'https';
  }

  $url = sprintf('%s://%s%s',
    $protocol,
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );

  $parts = parse_url($url);

  $port = $_SERVER['SERVER_PORT'];
  $scheme = $parts['scheme'];
  $host = $parts['host'];
  $path = @$parts['path'];
  $qs   = @$parts['query'];

  $port or $port = ($scheme == 'https') ? '443' : '80';

  if (($scheme == 'https' && $port != '443')
      || ($scheme == 'http' && $port != '80')) {
    $host = "$host:$port";
  }
  $url = "$scheme://$host$path";
  if ( ! $dropqs)
    return "{$url}?{$qs}";
  else
    return $url;
}


function error($msg) {
?>
  <h3>Something went wrong</h3>
  <p><?php echo $msg ?></p>
<?php
}

function uri_params() {
  $url = parse_url($_SERVER['REQUEST_URI']);
  $params = array();
  foreach (explode('&', $url['query']) as $p) {
    list($k, $v) = explode('=', $p);
    $params[$k] =$v;
  }
  return $params;
}

function request_token($tmhOAuth) {
  $code = $tmhOAuth->apponly_request(array(
    'without_bearer' => true,
    'method' => 'POST',
    'url' => $tmhOAuth->url('oauth/request_token', ''),
    'params' => array(
      'oauth_callback' => php_self(false),
    ),
  ));

  if ($code != 200) {
    error("There was an error communicating with Twitter. {$tmhOAuth->response['response']}");
    return;
  }

  // store the params into the session so they are there when we come back after the redirect
  $_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);

  // check the callback has been confirmed
  if ($_SESSION['oauth']['oauth_callback_confirmed'] !== 'true') {
    error('The callback was not confirmed by Twitter so we cannot continue.');
  } else {
    $url = $tmhOAuth->url('oauth/authorize', '') . "?oauth_token={$_SESSION['oauth']['oauth_token']}";
	  redirect_browser($url);
	/*?>
	<p>To complete the OAuth flow please visit URL: <a href="<?php echo $url ?>"><?php echo $url ?></a></p>
	<?php*/
  }
}

function access_token($tmhOAuth) {
  $params = uri_params();
  if ($params['oauth_token'] !== $_SESSION['oauth']['oauth_token']) {
    error('The oauth token you started with doesn\'t match the one you\'ve been redirected with. do you have multiple tabs open?');
    session_unset();
    return;
  }

  if (!isset($params['oauth_verifier'])) {
    error('The oauth verifier is missing so we cannot continue. did you deny the appliction access?');
    session_unset();
    return;
  }

  // update with the temporary token and secret
  $tmhOAuth->reconfigure(array_merge($tmhOAuth->config, array(
    'token'  => $_SESSION['oauth']['oauth_token'],
    'secret' => $_SESSION['oauth']['oauth_token_secret'],
  )));

  $code = $tmhOAuth->user_request(array(
    'method' => 'POST',
    'url' => $tmhOAuth->url('oauth/access_token', ''),
    'params' => array(
      'oauth_verifier' => trim($params['oauth_verifier']),
    )
  ));

  if ($code == 200) {
    $oauth_creds = $tmhOAuth->extract_params($tmhOAuth->response['response']);
	  return $oauth_creds;
	  /*
	?>
	<p>Congratulations, below is the user token and secret for @<?php echo htmlspecialchars($oauth_creds['screen_name']) ?>.
	Use these to make authenticated calls to Twitter using the application with
	consumer key: <?php echo htmlspecialchars($tmhOAuth->config['consumer_key']) ?></p>

	<p>User Token: <?php echo htmlspecialchars($oauth_creds['oauth_token']) ?><br />
	User Secret: <?php echo htmlspecialchars($oauth_creds['oauth_token_secret']) ?></p>
	<?php*/
  }
	return false;
}


$params = uri_params();
if (!isset($params['oauth_token'])) {
  // Step 1: Request a temporary token and
  // Step 2: Direct the user to the authorize web page
  request_token($tmhOAuth);
} else {
  // Step 3: This is the code that runs when Twitter redirects the user to the callback. Exchange the temporary token for a permanent access token
  $oauth_creds = access_token($tmhOAuth);
	if($oauth_creds && isset($oauth_creds['oauth_token']) && !empty($oauth_creds['oauth_token']) && isset($oauth_creds['oauth_token_secret']) && !empty($oauth_creds['oauth_token_secret'])){
		// yay we're connected to twitter!
		// write these to the database so we can do stuff on the backend.
		$twitter_account->update('twitter_name',$oauth_creds['screen_name']);
		$twitter_account->update('twitter_id',$oauth_creds['user_id']);
		$twitter_account->update('user_key',$oauth_creds['oauth_token']);
		$twitter_account->update('user_secret',$oauth_creds['oauth_token_secret']);
		$twitter_account->update('last_checked',time());
		set_message('Successfully connected to Twitter');
		redirect_browser(module_social_twitter::link_open($social_twitter_id));
	}else{
		echo 'Failed to connect to twitter';
		echo $tmhOAuth->response['response'];
	}
}

}
