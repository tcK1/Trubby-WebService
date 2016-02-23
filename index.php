<?php

header();

echo 'Current PHP version: ' . phpversion() . "\n";

define("CONSUMER_KEY", "dgqcifzjqksh");
define("CONSUMER_SECRET", "73Ft6jKqe3A7sCsc");

$oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET);

$request_token_response = $oauth->getRequestToken('https://api.linkedin.com/uas/oauth/requestToken');

if($request_token_response === FALSE) {
        throw new Exception("Failed fetching request token, response was: " . $oauth->getLastResponse());
} else {
        $request_token = $request_token_response;
}



print "Request Token:\n";
printf("    - oauth_token        = %s\n", $request_token['oauth_token']);
printf("    - oauth_token_secret = %s\n", $request_token['oauth_token_secret']);
print "\n";

?>