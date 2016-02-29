<?php

$aux = $_REQUEST['URL'];

if (strcasecmp($aux, 't') == 0){
     header('Location: /test/oauth_test.php');
} else echo $aux;

//Verifica o tipo de requisição

//Faz a combinação metodo + post/get e manda para a API

?>