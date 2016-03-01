<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/func.php';

define('SERVIDOR', 'http://' . $_SERVER['SERVER_NAME']); // Caminho do servidor

$URL = $_REQUEST['URL']; // Variaveis recebidas após a primeira "/"
$METODO = (string) $_SERVER['REQUEST_METHOD']; // Método de chamada da API

// Cria as variáveis em relação a url recebida
list($CHAVE, $SEGREDO, $SECAO, $USUARIO, $PRODUTO) = explode("/", $URL);

// Caso as variaveis de usuario e produto estejam definidas, adiciona elas ao array de parametros
$parametros = array();
if (!empty($USUARIO)){
    $parametros['USUARIO'] = $USUARIO;
}
if (!empty($PRODUTO)){
    $parametros['PRODUTO'] = $PRODUTO;
}

$entrada = leJSON(); // Dados recebidos pela requisição HTTP

// Caso exista conteudo no corpo da requisição HTTP, ele concatena com os dados da URL
if(!empty($entrada)) $parametros = array_merge($entrada, $parametros);

// Variáveis para autenticação do OAuth
$id = 1;

// Verifica se ja existe a variavel de opções na session.
$opcoes = array(
    'consumer_key' => (string) $CHAVE,
    'consumer_secret' => (string) $SEGREDO,
    'server_uri' => SERVIDOR,
    'request_token_uri' => SERVIDOR . '/oauth/request_token.php',
    'authorize_uri' => SERVIDOR . '/oauth/authorize.php',
    'access_token_uri' => SERVIDOR . '/oauth/access_token.php'
);

session_start();
if(isset($_SESSION['opcoes'])) $opcoes = $_SESSION['opcoes'];

OAuthStore::instance('Session', $opcoes);

if (empty($_GET['oauth_token'])) { // Caso ainda não possua um token, faz a requisição

    // Salva as variáveis na session para quando voltar do redirecionamento
    $_SESSION['METODO'] = $METODO;
    $_SESSION['SECAO'] = $SECAO;
    $_SESSION['opcoes'] = $opcoes;
    $_SESSION['parametros'] = $parametros;

    $tokenResultParams = OAuthRequester::requestRequestToken($opcoes['consumer_key'], $id);

    header('Location: ' . $opcoes['authorize_uri'] .
        '?oauth_token=' . $tokenResultParams['token'] . 
        '&oauth_callback=' . urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
}
else { // Ja com o token, pode fazer o chamado

    $oauthToken = $_GET['oauth_token'];
    $tokenResultParams = $_GET;

    OAuthRequester::requestAccessToken($_SESSION['opcoes']['consumer_key'], $tokenResultParams['oauth_token'], $id, 'POST', $_GET);

    // De acordo com a seção definida na URL, redireciona para a página responsalvel.

    switch ($_SESSION['SECAO']){
        case 'estoque':
            $chamado = new OAuthRequester(SERVIDOR . '/controle/estoque.php', $_SESSION['METODO'], $_SESSION['parametros']);
            break;
        case 'receita':
            $chamado = new OAuthRequester(SERVIDOR . '/controle/receita.php', $_SESSION['METODO'], $_SESSION['parametros']);
            break;
        case 'cardapio':
            $chamado = new OAuthRequester(SERVIDOR . '/controle/cardapio.php', $_SESSION['METODO'], $_SESSION['parametros']);
            break;
        case 'caixa':
            $chamado = new OAuthRequester(SERVIDOR . '/controle/caixa.php', $_SESSION['METODO'], $_SESSION['parametros']);
            break;
        case 'teste':
            $chamado = new OAuthRequester(SERVIDOR . '/test_request.php', $_SESSION['METODO'], $_SESSION['parametros']);
            break;
        default:
            header('Location: /index.php'); // **TROCAR POR PAG DE ERRO DE CHAMADA**
            break;
    }

    $resultado = $chamado->doRequest(0); // Executa a função
    
    if ($resultado['code'] == 200) {
        //echo escreveJSON($resultado['body']);
        echo $resultado['body'];
    }
    else {
        echo 'Error';
    }
}


