<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
if(!isset($_SESSION)): session_start(); endif;

// Add a header indicating this is an OAuth server
header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] .
     '/vendor/oauth-php/oauth-php/example/server/www/services.xrds.php');
     
// Connect to database
$db = new PDO('mysql:host=localhost;dbname=oauth', 'ztck', '12346Kaic');

// Create a new instance of OAuthStore and OAuthServer
$store = OAuthStore::instance('PDO', array('conn' => $db));
$server = new OAuthServer();