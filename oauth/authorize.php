<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

// Variaveis na session
//$dados = $_SESSION['opcoes'];

// Variaveis no header
$headers = getallheaders();

// Três informações serão recebidas do cliente:
// @param string chave   -> Dados de acesso a api
// @param string segredo -> Dados de acesso a api
// @param string usuario -> email do usuario

// Armazena o log
$stmt = $db->prepare(
    'INSERT INTO log (chave, email)
    VALUES (:chave, :email)');
$stmt->execute(array(
    'chave' => $headers[chave],    // Session
    'email' => $headers[email]           // Header
));

// Ve se o valor recebido é valido e recupera o id do usuário
$stmt = $db->prepare(
    'SELECT osr_usa_id_ref 
    FROM oauth_server_registry 
    WHERE osr_consumer_key = :consumer_key');
$stmt->execute(array(
    //'consumer_key' => $dados[consumer_key]    // Session
    'consumer_key' => $headers[chave]           // Header
));
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    die;
}

$id = $resultado['osr_usa_id_ref'];
$stmt->closeCursor();

// Ve se existe um token valido na atual requisição
$rs = $server->authorizeVerify();

$autorizar = TRUE;

// Ve se a conbinação de chave e segredo existe
$stmt = $db->prepare(
    'SELECT * 
    FROM oauth_server_registry 
    WHERE osr_consumer_key = :consumer_key 
    AND osr_consumer_secret = :consumer_secret');
    
$stmt->execute(array(
    //'consumer_key' => $dados[consumer_key],       // Session
    //'consumer_secret' => $dados[consumer_secret]
    'consumer_key' => $headers[chave],              // Header
    'consumer_secret' => $headers[segredo]
));
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    echo 'Combinação chave-segredo invalida';
    $autorizar = FALSE;
    die();
}

// Ve se existe um usuario com o email
$stmt = $dbt->prepare(
    'SELECT id_usuario 
    FROM usuarios 
    WHERE email = :email');
    
$stmt->execute(array(
    //'email' => $dados[email],     // Session
    'email' => $headers[email],     // Header
));
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    echo 'E-mail não cadastrado';
    $autorizar = FALSE;
    die();
}

// Faz a requisição ficar autorizada ou não
// Retorna para o oauth_callback
$server->authorizeFinish($autorizar, $id);