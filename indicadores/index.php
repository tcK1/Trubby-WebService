<?php
    
    include "funcoes.php";

    $quantidade = quantidadeProdutoVendidoTempo('abobora', 10,  '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - quantidade: ".$quantidade."<br>";
    
    $quantidadeEstoque = quantidadeEstoqueGastoTempo('conhaque', 10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - estoque: ".$quantidadeEstoque."<br>";
    
    $ticketMedio = mediaPorVenda(10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - ticket medio: ".$ticketMedio."<br>";
    
    $faturamento = faturamentoEmTempo(10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - faturamento tempo: ".$faturamento."<br>";
    
    
    $fatura = faturamentoDeterminadoProduto('abobora', 10,  '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - faturamento abobora: ".$fatura."<br>";
    
    $fatura = mediaFaturamentoDiario(10,  '0000-00-00 00:00:00', '3000-00-00 00:00:00');
    echo "INDEX - faturamento medio diario: ".$faturamento."<br>";
    
    $resultado = previsaoFimEstoque(10);
    print_r($resultado);
        
    
?>