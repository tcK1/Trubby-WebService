<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
if(!isset($_SESSION)): session_start(); endif;

// Adiciona o header que indica que este servidor é autenticado por OAuth
header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] .
     '/vendor/oauth-php/oauth-php/example/server/www/services.xrds.php');
     
// Cria a database para autenticação
$db = new PDO('mysql:host=54.207.22.190:3306;dbname=oauth', 'trubby', 'raiztrubby');
     
// Cria a database para modificação no banco
$dbt = new PDO('mysql:host=54.207.22.190:3306;dbname=trubby', 'trubby', 'raiztrubby');

// Cria nova instancia do OAuthStore e OAuthServer
$store = OAuthStore::instance('PDO', array('conn' => $db));
$server = new OAuthServer();

// Variaveis para uso global (por exemplo dentro de funções)
$GLOBALS['db'] = $db;
$GLOBALS['dbt'] = $dbt;
$GLOBALS['store'] = $store;
$GLOBALS['server'] = $server;
