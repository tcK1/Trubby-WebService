<?php
require_once '../vendor/autoload.php';

define('OAUTH_HOST', 'http://' . $_SERVER['SERVER_NAME']);

$id = 1;

// Init the OAuthStore
$options = array(
    'consumer_key' => 'd43e51109bf59e2af9526e131fd8041f056d07f89',
    'consumer_secret' => 'c02ea3db6c45d66bfc27bdf4bf6eed80',
    'server_uri' => OAUTH_HOST,
    'request_token_uri' => OAUTH_HOST . '/oauth/request_token.php',
    'authorize_uri' => OAUTH_HOST . '/oauth/login.php',
    'access_token_uri' => OAUTH_HOST . '/oauth/access_token.php'
);

OAuthStore::instance('Session', $options);

if (empty($_GET['oauth_token'])) {
    // get a request token
    $tokenResultParams = OAuthRequester::requestRequestToken($options['consumer_key'], $id);
    
    header('Location: ' . $options['authorize_uri'] .
        '?oauth_token=' . $tokenResultParams['token'] . 
        '&oauth_callback=' . urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
}
else {
    // get an access token
    $oauthToken = $_GET['oauth_token'];
    $tokenResultParams = $_GET;

    OAuthRequester::requestAccessToken($options['consumer_key'], $tokenResultParams['oauth_token'], $id, 'POST', $_GET);
    $request = new OAuthRequester(OAUTH_HOST . '/test_request.php', 'GET', $tokenResultParams);
    $result = $request->doRequest(0);
    if ($result['code'] == 200) {
        var_dump($result['body']);
    }
    else {
        echo 'Error';
    }
}
