<?php
    //funcao para retornar quanto de tal item foi vendido em tanto tempo
    function quantidadeProdutoVendidoTempo($nomeProduto, $idUsuario,  $dataInicial, $dataFinal){
         $BD = new mysqli('54.207.22.190:3306', 'trubby', 'raiztrubby', 'trubby');
        $quantidade =0;
        $sql = "SELECT SUM( `quantidade` ) FROM `vendas_itens` WHERE `id_venda` IN (
                SELECT `id_venda` FROM `vendas` WHERE `id_usuario` = '$idUsuario' AND `data` 
                BETWEEN '$dataInicial' AND '$dataFinal') AND `id_produto` = (
                SELECT `id_produto` FROM `produto` WHERE `nome` = '$nomeProduto')";
        //echo $sql."<br>";
        
       
        $query = $BD->query($sql);
        while ($dados = $query->fetch_assoc()) {
            $quantidade = $dados['SUM( `quantidade` )'];
        }
        mysqli_close($BD);
        return $quantidade;
    }
    
    //funcao para retornar quanto gastou de tal item do estoque em tanto tempo
    function quantidadeEstoqueGastoTempo($nomeEstoque, $idUsuario, $dataInicial, $dataFinal){
        $quantidade =0;
        $BD = new mysqli('54.207.22.190:3306', 'trubby', 'raiztrubby', 'trubby');
        //primeiro ve quais produtos usam aquele item do estoque
        $sql = "SELECT `id_ficha` , `quantidade_brt` FROM `ingredientes_uso` WHERE `id_estoque` = (
                SELECT `id_produto` FROM `produto` WHERE `nome` = '$nomeEstoque' AND `id_produto`IN (
                SELECT `id_produto` FROM `estoque`WHERE `id_usuario` ='$idUsuario'))";
        $query = $BD->query($sql);
        
        //chama a funcao quantidadeProdutoVendidoTempo para todos esses produtos
        while ($dados = $query->fetch_assoc()) {
            $idProduto = $dados['id_ficha'];
            $sql = "SELECT SUM( `quantidade` ) FROM `vendas_itens` WHERE `id_venda` IN (
                    SELECT `id_venda` FROM `vendas` WHERE `id_usuario` = '$idUsuario' AND `data` 
                    BETWEEN '$dataInicial' AND '$dataFinal') AND `id_produto` = $idProduto";
            $query2 = $BD->query($sql);
            while ($dados2 = $query2->fetch_assoc()) {
                $produtosVendidos = $dados2['SUM( `quantidade` )'];
                $quantidade = $quantidade + $produtosVendidos*$dados['quantidade_brt'];
            }
            
        }
        mysqli_close($BD);
        return $quantidade;
    }
    
    //calcula a media de gasto por cleinte em um determinado periodo de tempo
    function ticketMedioTempo($idUsuario, $dataInicial, $dataFinal){
        $totalVenda =0;
        $numeroClientes =0;
        $BD = new mysqli('54.207.22.190:3306', 'trubby', 'raiztrubby', 'trubby');
        //ve o id das vendas no tempo determinado
        $sql = "SELECT `id_venda` FROM `vendas` WHERE `id_usuario` =$idUsuario AND `data` BETWEEN '$dataInicial' AND '$dataFinal'";
        //echo $sql."<br>";
        $query = $BD->query($sql);
        while ($dados = $query->fetch_assoc()) {
            $numeroClientes++;
            $idVenda = $dados['id_venda'];
            //ve o preco de cada venda
            $sql2 = "SELECT SUM( `quantidade` ) FROM `vendas_itens` WHERE `id_venda` =$idVenda";
            //echo $sql2."<br>";
            $query2 = $BD->query($sql2);
            while ($dados2 = $query2->fetch_assoc()) {
                $precoVenda = $dados2['SUM( `quantidade` )'];
                $totalVenda = $totalVenda + $precoVenda;
            }
        }
        mysqli_close($BD);
        $ticketMedio = $totalVenda/$numeroClientes;
        return $ticketMedio;
    }
?>