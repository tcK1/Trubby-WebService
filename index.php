<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

define('SERVIDOR', 'http://' . $_SERVER['SERVER_NAME']); // Caminho do servidor

$URL = $_REQUEST['URL']; // Variaveis recebidas após a primeira "/"
$METODO = (string) $_SERVER['REQUEST_METHOD']; // Método de chamada da API

// Caso o usuário da API queira registrar uma nova conta, sera redirecionado para a página
if (strcasecmp($URL, 'registrar') == 0){
    header('Location: /registrar.php'); die();
}

// Cria as variáveis em relação a url recebida
list($SECAO, $PRODUTO, $EXTRA) = explode("/", $URL);

// Le o Header da requisição
$header = getallheaders();

// Caso as variaveis de usuario e produto estejam definidas, adiciona elas ao array de parametros
$parametros = array();
if (!empty($SECAO)){
    $parametros['SECAO'] = $SECAO;
}
if (!empty($header[email])){
    $parametros[email] = $header[email];
}
if (!empty($PRODUTO)){
    $parametros['PRODUTO'] = $PRODUTO;
}
if (!empty($EXTRA)){
    $parametros['EXTRA'] = $EXTRA;
}

$entrada = json_decode(file_get_contents('php://input'), true); // Dados recebidos pela requisição HTTP

// Caso exista conteudo no corpo da requisição HTTP, ele concatena com os dados da URL
if(!empty($entrada)) $parametros = array_merge($parametros, $entrada);

// Serializa o array de parametros para passar para a requisição
$parametrosSerializados[0] = serialize($parametros);

// Variáveis para autenticação do OAuth
$id = 1;

// Verifica se ja existe a variavel de opções na session.
$opcoes = array(
    'consumer_key' => (string) $header['chave'],
    'consumer_secret' => (string) $header['segredo'],
    'server_uri' => SERVIDOR,
    'request_token_uri' => SERVIDOR . '/oauth/request_token.php',
    'authorize_uri' => SERVIDOR . '/oauth/authorize.php',
    'access_token_uri' => SERVIDOR . '/oauth/access_token.php'
);

session_start();
if(isset($_SESSION['opcoes'])) $opcoes = $_SESSION['opcoes'];

OAuthStore::instance('Session', $opcoes);

try {
    if (empty($_GET['oauth_token'])) { // Caso ainda não possua um token, faz a requisição
    
        // Salva as variáveis na session para quando voltar do redirecionamento
        $_SESSION['METODO'] = $METODO;
        $_SESSION['SECAO'] = $SECAO;
        $_SESSION['opcoes'] = $opcoes;
        $_SESSION['parametros'] = $parametrosSerializados;
    
        $tokenResultParams = OAuthRequester::requestRequestToken($opcoes['consumer_key'], $id);
    
        header('Location: ' . $opcoes['authorize_uri'] .
            '?oauth_token=' . $tokenResultParams['token'] . 
            '&oauth_callback=' . urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
    }
    else { // Ja com o token, pode fazer o chamado
    
        $oauthToken = $_GET['oauth_token'];
        $tokenResultParams = $_GET;
    
        OAuthRequester::requestAccessToken($_SESSION['opcoes']['consumer_key'], $tokenResultParams['oauth_token'], $id, 'POST', $_GET);
    
        // Extrutura do chamado
        $chamado = new OAuthRequester(SERVIDOR . '/test_request.1.php', $_SESSION['METODO'], $_SESSION['parametros']);
        
        $resultado = $chamado->doRequest(0); // Executa a função
        
        if ($resultado['code'] == 200) {
            header('Content-Type: application/json; charset=utf-8');
                
            echo $resultado['body'];
            
            session_unset();
            session_destroy();
            die();
        }
        else {
            echo 'Error';
            echo $resultado['code'];
            
            session_unset();
            session_destroy();
            die();
        }
    }
} catch (OAuthException2 $e) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: OAuth realm=""');
    header('Content-Type: application/json; charset=utf-8');

    $erro = $e->getMessage();
    $return = array ('mensagem' => $erro);
    echo json_encode($return);
    
    session_unset();
    session_destroy();
    die();
    exit();
}