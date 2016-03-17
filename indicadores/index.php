<?php
    
    include "funcoes.php";

    $quantidade = quantidadeProdutoVendidoTempo('abobora', 10,  '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - quantidade: ".$quantidade."<br>";
    
    $quantidadeEstoque = quantidadeEstoqueGastoTempo('conhaque', 10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - estoque: ".$quantidadeEstoque."<br>";
    
    $ticketMedio = ticketMedioTempo(10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - ticket medio: ".$ticketMedio."<br>";
?>