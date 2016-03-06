<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

session_start();

if (OAuthRequestVerifier::requestIsSigned()) {
    try {
        $req = new OAuthRequestVerifier();
        $id = $req->verify();
    
	    // If we have an ID, then login as that user (for this requeste
        if ($id) {
            
            //$entradaSerializada = unserialize($_POST[0]);
            
            // Formata os dados em um JSON
            echo json_encode(unserialize($_POST[0]));
            
            /*
            print_r('SESSION:');
            echo '<br>';
            print_r($_SESSION);
            print_r('GET:');
            echo '<br>';
            print_r($_GET);
            print_r('POST:');
            echo '<br>';
            print_r($_POST);
            */
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
