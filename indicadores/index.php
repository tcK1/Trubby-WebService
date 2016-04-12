<?php
    
    include "funcoes.php";

    $quantidade = quantidadeProdutoVendidoTempo('abobora', 10,  '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    //echo "INDEX - quantidade: ".$quantidade."<br>";
    
    $quantidadeEstoque = quantidadeEstoqueGastoTempo('conhaque', 10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - estoque gasto no tempo (conhaque): ".$quantidadeEstoque."<br>";
    
    $ticketMedio = mediaPorVenda(10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - ticket medio: ".$ticketMedio."<br>";
    
    $faturamento = faturamentoEmTempo(10, '0000-00-00 00:00:00', '1000-00-00 00:00:00');
    echo "INDEX - faturamento tempo: ".$faturamento."<br>";
    
    
    $fatura = faturamentoDeterminadoProduto('abobora', 10,  '0000-00-00 00:00:00', '9000-00-00 00:00:00');
    echo "INDEX - faturamento abobora: ".$fatura."<br>";
    
    $fat = mediaFaturamentoDiario(10,  '0000-00-00', '3000-00-00');
    echo "INDEX - faturamento medio diario: ".$fat."<br>";
    
    $resultado = previsaoFimEstoque(10);
    echo "previsao fim do estoque:  ";
    print_r($resultado);
    echo "<br>";
    echo "<br>";
    echo "pacotes de venda:  ";
    $resultado = vendaCasada(10);
    print_r($resultado);
    
    echo "<br>";
    $fatura = mediaFaturamentoHorario(10, '00:00:00', '30:00:00');
    echo "INDEX - faturamento medio horario: ".$fatura."<br>";
?>