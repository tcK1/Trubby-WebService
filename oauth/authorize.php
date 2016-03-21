<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

$dados = $_SESSION['opcoes'];

// Ve se o valor recebido é valido e recupera o id do usuário
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
// Ve se existe um token valido na atual requisição
$rs = $server->authorizeVerify();

// Ve se a conbinação de chave e segredo existe
$stmt = $db->prepare(
    'SELECT * FROM oauth_server_registry 
    WHERE osr_consumer_key = :consumer_key 
    AND osr_consumer_secret = :consumer_secret');
    
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

// Set the request token to be authorized or not authorized
// Faz a requisição ficar autorizada ou não
// Retorna para o oauth_callback
$server->authorizeFinish($autorizar, $id);