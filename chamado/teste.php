<?php
namespace teste;
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

function funcao($parametros){
    $parametros['lol'] = 'looool';
    return $parametros;
}