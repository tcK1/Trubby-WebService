<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

// 
$dados = array_values($_SESSION)[0];
/*print "<pre>";
print_r($aux);
print_r($aux[consumer_key]);
print_r($aux[consumer_secret]);
print "</pre>";
/*print "<pre>";
print_r($_SESSION);
print "</pre>";
print "<pre>";
print_r($_GET);
print "</pre>";
*/

// check if the login information is valid and get the user's ID
$stmt = $db->prepare('SELECT osr_usa_id_ref FROM oauth_server_registry WHERE osr_consumer_key = :consumer_key');
$stmt->execute(array(
    'consumer_key' => $dados[consumer_key]
));
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    die;
}

$id = $resultado['osr_usa_id_ref'];
$stmt->closeCursor();

// Check if there is a valid request token in the current request.
// This returns an array with the consumer key, consumer secret, token,
// token secret and token type.
$rs = $server->authorizeVerify();

// See if the user clicked the 'allow' submit button (or whatever you choose)
//$authorized = array_key_exists('allow', $_POST);

$stmt = $db->prepare('SELECT * FROM oauth_server_registry WHERE osr_consumer_key = :consumer_key AND osr_consumer_secret = :consumer_secret');
$stmt->execute(array(
    'consumer_key' => $dados[consumer_key],
    'consumer_secret' => $dados[consumer_secret]
));
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$autorizar = TRUE;

if (!$resultado) {
    echo $resultado;
    $autorizar = FALSE;
    die();
}

/*
print_r($_POST);
echo $authorized;
var_dump($authorized);
die();
*/

// Set the request token to be authorized or not authorized
// When there was a oauth_callback then this will redirect to the consumer
//$server->authorizeFinish($authorized, $id);
$server->authorizeFinish($autorizar, $id);