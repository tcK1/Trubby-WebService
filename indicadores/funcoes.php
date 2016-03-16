<?php
    //funcao para retornar quanto de tal item foi vendido em tanto tempo
    function quantidadeProdutoVendidoTempo($nomeProduto, $idUsuario,  $dataInicial, $dataFinal){
        $quantidade =0;
        $sql = "SELECT SUM( `quantidade` ) FROM `vendas_itens` WHERE `id_venda` IN (
                SELECT `id_venda` FROM `vendas` WHERE `id_usuario` = '$idUsuario' AND `data` 
                BETWEEN '$dataInicial' AND '$dataFinal') AND `id_produto` = (
                SELECT `id_produto` FROM `produto` WHERE `nome` = '$nomeProduto')";
        //echo $sql."<br>";
        
        $BD = new mysqli('54.207.22.190:3306', 'trubby', 'raiztrubby', 'trubby');
        $query = $BD->query($sql);
        while ($dados = $query->fetch_assoc()) {
          $quantidade = $dados['SUM( `quantidade` )'];
        }
        return $quantidade;
    }
    
    //funcao para retornar quanto gastou de tal item do estoque em tanto tempo
    function ($nomeEstoque, $dataInicial, $dataFinal, $BD){
        $quantidade =0;
        return $quantidade;
    }
?>