<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

include $_SERVER['DOCUMENT_ROOT'].'/chamado/teste.php';

session_start();

if (OAuthRequestVerifier::requestIsSigned()) {
    try {
        $req = new OAuthRequestVerifier();
        $id = $req->verify();
	    // If we have an ID, then login as that user (for this requeste
        if ($id) {
            
            // Recupere os valores nos arrays globais GET ou POST
            if(!empty($_GET))   $parametros = unserialize($_GET[0]);
            else                $parametros = unserialize($_POST[0]);
            
            
            // Formata os dados em um JSON
            echo json_encode(teste\funcao($parametros));

        }
    }
    catch (OAuthException $e) {
        // The request was signed, but failed verification
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: OAuth realm=""');
        header('Content-Type: text/plain; charset=utf8');

        echo $e->getMessage();
        exit();
    }
}