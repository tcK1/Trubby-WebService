<?php
require_once 'include/common.php';

if (OAuthRequestVerifier::requestIsSigned()) {
    try {
        $req = new OAuthRequestVerifier();
        $id = $req->verify();

	// If we have an ID, then login as that user (for this requeste
        if ($id) {
            echo 'Hello ' . $id;
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
