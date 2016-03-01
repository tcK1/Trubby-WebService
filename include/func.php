<?php

// Captura o corpo JSON da requisição HTTP realizada
function leJSON(){
    return json_decode(file_get_contents('php://input'), true);
}


// Captura o corpo JSON da requisição HTTP realizada
function escreveJSON($array){
 
    // Declara o tipo de conteúdo a ser enviado para o cliente
    header('Content-Type: application/json; charset=utf-8');
    
    // Formata os dados em um JSON
    return json_encode($array);
}
