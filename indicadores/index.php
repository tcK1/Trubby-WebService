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
    
    echo "<br>";
    $produtos= produtosVendidosTempo(10,  '0000-00-00 00:00:00', '9000-00-00 00:00:00');
    //print_r($produtos);
    
    $produtos = porcentagem($produtos, 'faturamento');
    //funcao que pega o arranjo de produtos e calcula a porcentagem do faturamento
    function porcentagem($array, $tipo){
        $faturamento_total=0;
        foreach ($array as $value) {
            $faturamento_total +=$value[$tipo];
        }
        $aux=0;
        for($i = 0; $i < count($array); $i++) {
            $array[$i][$tipo]=($array[$i][$tipo]*100)/$faturamento_total+$aux;
            $aux=$array[$i][$tipo];
        }
        echo $array[0][$tipo];
        return $array;
    }
    
    echo "<br><br>";
    $estoqueABC = precoEstoqueGastoTempo(10,  '0000-00-00 00:00:00', '9000-00-00 00:00:00');
    $estoqueABC = porcentagem($estoqueABC, 'total');
    //print_r($estoqueABC);
    
    
?>

<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        
        <!-- funcao para fazer grafico de faturamento-->
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            
            var produtos = <?php echo json_encode($produtos, JSON_PRETTY_PRINT) ?>;
            
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Produto', 'Porcentagem do faturamento total'],
                    [produtos[0].nome, produtos[0].faturamento]
                ]);
                
                for (i = 1; i < produtos.length; i++) {
                    data.addRow([produtos[i].nome,produtos[i].faturamento]);
                }
                
                var options = {
                    title: 'Curva ABC de faturamento',
                    hAxis: {title: 'Produto',  titleTextStyle: {color: '#333'}},
                    vAxis: {minValue: 0, maxValue:100}
                };
                
                var chart = new google.visualization.AreaChart(document.getElementById('curvaABCFaturamento'));
                chart.draw(data, options);
            }
        </script>
        
        
        
        <!-- funcao para fazer grafico de estoque-->
        <script type="text/javascript">
            
            google.charts.setOnLoadCallback(drawChart);
            
            var estoque = <?php echo json_encode($estoqueABC, JSON_PRETTY_PRINT) ?>;
            
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Estpqie', 'Porcentagem de custo'],
                    [estoque[0].nome, estoque[0].total]
                ]);
                
                for (i = 1; i < estoque.length; i++) {
                    data.addRow([estoque[i].nome,estoque[i].total]);
                }
                
                var options = {
                    title: 'Curva ABC de estoque',
                    hAxis: {title: 'Estoque',  titleTextStyle: {color: '#333'}},
                    vAxis: {minValue: 0, maxValue:100}
                };
                
                var chart = new google.visualization.AreaChart(document.getElementById('curvaABCEstoque'));
                chart.draw(data, options);
            }
        </script>
    </head>
    <body>
        
        <div id="curvaABCFaturamento"></div>
        
        <div id="curvaABCEstoque"></div>
        
    </body>
</html>