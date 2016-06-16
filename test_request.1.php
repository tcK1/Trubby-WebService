<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

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
            unset($parametros['SECAO']);
            
            $resposta;
            
            // Para cada seção um conjunto de verbos com ações diferentes
            switch($secao){
                // Requisições para a area de usuário
                case 'usuario':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/usuario.php';
                    switch($metodo){
                        case 'POST':    $resposta = cadastro($parametros);              break;
                        case 'PUT':     $resposta = login_valida($parametros);          break;
                        case 'DELETE':  $resposta = deleta($parametros);                break;
                        default:        $resposta = requisicao_incorreta();             break;
                    }
                    break;
                // Requisições para a area de estoque
                case 'estoque':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/estoque.php';
                    switch ($metodo) {
                        case 'POST':    $resposta = insere_modifica($parametros);       break;
                        case 'GET':     $resposta = lista($parametros);                 break;
                        case 'DELETE':  $resposta = estoque\deleta($parametros);        break;
                        default:        $resposta = requisicao_incorreta();             break;
                    }
                    break;
                // Requisições para a area de receitas
                case 'receitas':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/receitas.php';
                    switch ($metodo) {
                        case 'GET':     $resposta = lista($parametros);                 break;
                        case 'POST':    $resposta = insere($parametros);                break;
                        case 'PUT':     $resposta = modifica($parametros);              break;
                        case 'OPTIONS': $resposta = ingredientes_de_ficha($parametros); break; 
                        case 'DELETE':  $resposta = deleta($parametros);                break;
                        default:        $resposta = requisicao_incorreta();             break;
                    }
                    break;
                // Requisições para a area de cardápio
                case 'cardapio':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/cardapio.php';
                    switch ($metodo) {
                        case 'GET':     $resposta = lista($parametros);                 break;
                        case 'POST':    $resposta = insere($parametros);                break;
                        case 'PUT':     $resposta = modifica($parametros);              break;
                        case 'OPTIONS': $resposta = opcoes($parametros);                break;
                        case 'DELETE':  $resposta = deleta($parametros);                break;
                        default:        $resposta = requisicao_incorreta();             break;
                    }
                    break;
                // Requisições para a area de caixa
                case 'caixa':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/caixa.php';
                    switch ($metodo) {
                        case 'GET':     $resposta = lista($parametros);                 break;
                        case 'POST':    $resposta = insere($parametros);                break;
                        case 'DELETE':  $resposta = cancela($parametros);               break;
                        default:        $resposta = requisicao_incorreta();             break;
                    }
                    break;
                // Requisições para teste
                case 'teste':
                    include $_SERVER['DOCUMENT_ROOT'].'/chamado/teste.php';
                    switch($metodo){
                        default:        $resposta = funcao($parametros);                      break;
                    }
                    break;
                // Caso uma area não tenha cido especificada
                default:
                    $resposta = requisicao_incorreta();
                    break;
            }
            
            // Imprime a resposta em JSON
            echo json_encode($resposta);
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