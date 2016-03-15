<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
foreach (glob($_SERVER['DOCUMENT_ROOT'].'/chamado/*.php') as $secao){
    include $secao;
}

session_start();

if (OAuthRequestVerifier::requestIsSigned()) {
    try {
        $req = new OAuthRequestVerifier();
        $id = $req->verify();
        
	    // Caso o id tenha retornado um valor, usamos ele para fazer a requisição
        if ($id) {
            
            // Recupere os valores nos arrays globais GET ou POST
            if(!empty($_GET))   $parametros = unserialize($_GET[0]);
            else                $parametros = unserialize($_POST[0]);
            
            $metodo = $_SERVER['REQUEST_METHOD']; // Define qual é o método
            $secao = $parametros['SECAO']; // Define qual parte da api será utilizada
            
            $resposta;
            
            // Para cada seção um conjunto de verbos com ações diferentes
            switch($secao){
                // Requisições para a area de usuário
                case 'usuario':
                    switch($metodo){
                        case 'POST':    $resposta = usuario\cadastro($parametros);                  break;
                        case 'PUT':     $resposta = usuario\login_valida($parametros);              break;
                        case 'DELETE':  $resposta = usuario\deleta($parametros);                    break;
                        default:        $resposta = requisicao_incorreta();                         break;
                    }
                    break;
                // Requisições para a area de estoque
                case 'estoque':
                    switch ($metodo) {
                        case 'POST':    $resposta = estoque\insere_modifica($parametros);           break;
                        case 'GET':     $resposta = estoque\lista($parametros);                     break;
                        case 'DELETE':  $resposta = estoque\deleta($parametros);                    break;
                        default:        $resposta = requisicao_incorreta();                         break;
                    }
                    break;
                // Requisições para a area de receitas
                case 'receitas':
                    switch ($metodo) {
                        case 'GET':     $resposta = receitas\lista($parametros);                    break;
                        case 'POST':    $resposta = receitas\insere($parametros);                   break;
                        case 'PUT':     $resposta = receitas\modifica($parametros);                 break;
                        case 'OPTIONS': $resposta = receitas\ingredientes_de_ficha($parametros);    break; 
                        case 'DELETE':  $resposta = receitas\deleta($parametros);                   break;
                        default:        $resposta = requisicao_incorreta();                         break;
                    }
                    break;
                // Requisições para a area de cardápio
                case 'cardapio':
                    switch ($metodo) {
                        case 'GET':     $resposta = cardapio\lista($parametros);                    break;
                        case 'POST':    $resposta = cardapio\insere($parametros);                   break;
                        case 'PUT':     $resposta = cardapio\modifica($parametros);                 break;
                        case 'OPTIONS': $resposta = cardapio\opcoes($parametros);                   break;
                        case 'DELETE':  $resposta = cardapio\deleta($parametros);                   break;
                        default:        $resposta = requisicao_incorreta();                         break;
                    }
                    break;
                // Requisições para a area de caixa
                case 'caixa':
                    switch ($metodo) {
                        case 'GET':     $resposta = caixa\lista($parametros);                       break;
                        case 'POST':    $resposta = caixa\insere($parametros);                      break;
                        case 'DELETE':  $resposta = caixa\cancela($parametros);                     break;
                        default:        $resposta = requisicao_incorreta();                         break;
                    }
                    break;
                // Caso uma area não tenha cido especificada
                default:
                    $resposta = requisicao_incorreta();
                    break;
            }
            
            // Imprime a resposta em JSON
            echo json_encode(unserialize($resposta));
            //print_r($_SERVER['REQUEST_METHOD']);
            
            // Formata os dados em um JSON
            //echo json_encode(unserialize($_GET[0]));
            teste();
        }
    }
    catch (OAuthException $e) {
        // O chamado estava com uma assinatura mas falhou na verificação
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: OAuth realm=""');
        header('Content-Type: text/plain; charset=utf8');

        echo $e->getMessage();
        exit();
    }
}

// Retorna um BAD REQUEST em caso de erro na requisição
function requisicao_incorreta(){
    header('HTTP/1.1 400 BAD REQUEST');
    die();
}

function teste(){
    echo json_encode(unserialize($_GET[0]));
}