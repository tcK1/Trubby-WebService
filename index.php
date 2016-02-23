<?php

$params = $_REQUEST['URL'];

echo $params . "<br>";

echo 'Current PHP version: ' . phpversion() . "<br>";

define("CONSUMER_KEY", "dgqcifzjqksh");
define("CONSUMER_SECRET", "73Ft6jKqe3A7sCsc");

$oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET);

$request_token_response = $oauth->getRequestToken('https://api.linkedin.com/uas/oauth/requestToken');

if($request_token_response === FALSE) {
		throw new Exception("Failed fetching request token, response was: " . $oauth->getLastResponse());
} else {
		$request_token = $request_token_response;
}



print "Request Token:<br>";
printf("    - oauth_token        = %s<br>", $request_token['oauth_token']);
printf("    - oauth_token_secret = %s<br>", $request_token['oauth_token_secret']);
print "<br>";

?>