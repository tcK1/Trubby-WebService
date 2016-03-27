<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';
    //funcao para retornar quanto de tal item foi vendido em tanto tempo
    function quantidadeProdutoVendidoTempo($nomeProduto, $idUsuario,  $dataInicial, $dataFinal){
        $quantidade =0;
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT SUM( `quantidade` ) FROM vendas_itens INNER JOIN vendas 
            ON vendas_itens.id_venda = vendas.id_venda
            AND id_usuario = :idUsuario
            AND id_produto = ( SELECT id_produto FROM produto WHERE nome = :nomeProduto )
            AND `data`
            BETWEEN :dataInicial AND :dataFinal');
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal,
            'nomeProduto' =>$nomeProduto
        ));
        //echo $sql."<br>";
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $quantidade = $resultado['SUM( `quantidade` )'];
        return $quantidade;
    }
    
    //funcao para retornar quanto gastou de tal item do estoque em tanto tempo
    function quantidadeEstoqueGastoTempo($nomeEstoque, $idUsuario, $dataInicial, $dataFinal){
        $quantidade =0;
        //primeiro ve quais produtos usam aquele item do estoque
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT `id_ficha` , `quantidade_brt`
            FROM `ingredientes_uso`
            WHERE `id_estoque` = (
            SELECT `estoque`.`id_produto`
            FROM `produto`
            INNER JOIN `estoque` ON `produto`.`id_produto` = `estoque`.`id_produto`
            AND `nome` = :nomeEstoque
            AND `estoque`.`id_produto`
            AND `estoque`.`id_usuario` = :idUsuario)'
        );
        $stmt->execute(array(
            'nomeEstoque' => $nomeEstoque,
            'idUsuario' =>$idUsuario
        ));
        
        $stmt2 = $GLOBALS['dbt']->prepare(
            'SELECT SUM( `quantidade` )
            FROM `vendas_itens`
            INNER JOIN `vendas` ON `vendas_itens`.`id_venda` = `vendas`.`id_venda`
            AND `vendas`.`id_usuario` = :idUsuario
            AND `data`
            BETWEEN :dataInicial
            AND :dataFinal
            AND `vendas_itens`.`id_produto` =:idProduto'
        );
        
        //chama a funcao quantidadeProdutoVendidoTempo para todos esses produtos
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idProduto = $dados['id_ficha'];
            $qtdBrt = $dados['quantidade_brt'];
            
            $stmt2->execute(array(
                'idUsuario' => $idUsuario,
                'dataInicial' =>$dataInicial,
                'dataFinal' =>$dataFinal, 
                'idProduto'=> $idProduto
            ));
            while ($dados2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $produtosVendidos = $dados2['SUM( `quantidade` )'];
                $quantidade = $quantidade + $produtosVendidos*$qtdBrt;
            }
            
        }
        return $quantidade;
    }
    
    //calcula a media de gasto por cleinte em um determinado periodo de tempo
    function ticketMedioTempo($idUsuario, $dataInicial, $dataFinal){
        $totalVenda =0;
        $numeroClientes =0;
        //ve o id das vendas no tempo determinado
		$stmt = $GLOBALS['dbt']->prepare(
            'SELECT `id_venda` FROM `vendas` WHERE `id_usuario` = :idUsuario 
			AND `data` BETWEEN :dataInicial AND :dataFinal'
		);
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal,
        ));
		//ve o preco de cada venda
		$stmt2 = $GLOBALS['dbt']->prepare(
            "SELECT SUM( `quantidade` ) FROM `vendas_itens` WHERE `id_venda` = :idVenda"
		);
		
		while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$numeroClientes++;
            $idVenda = $dados['id_venda'];
			
			$stmt2->execute(array(
                'idVenda' => $idVenda
            ));
			while ($dados2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $precoVenda = $dados2['SUM( `quantidade` )'];
                $totalVenda = $totalVenda + $precoVenda;
            }
		}
		$ticketMedio = $totalVenda/$numeroClientes;
        return $ticketMedio;
    }
    
    //calcular em toda a historia? ou da semana anterior? mes anterior? apenas nos dias que vendeu algo
    function mediaFaturamentoDiario($idUsuario, $dataInicial, $dataFinal){
         
    }
    
    //calcula o faturamento em determinado tempo (em um dia por exemplo, semana, mes...)
    function faturamentoEmTempo($idUsuario, $dataInicial, $dataFinal){
        $faturamento =0;
		$stmt = $GLOBALS['dbt']->prepare(
            "SELECT SUM( `preco_venda` )
			FROM `vendas_itens`
			INNER JOIN `vendas` ON `vendas_itens`.`id_venda` = `vendas`.`id_venda`
			AND `data`
			BETWEEN :dataInicial
			AND :dataFinal
			AND `id_usuario` = :idUsuario"
		);
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal,
        ));
        
		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
		$faturamento = $resultado['SUM( `preco_venda` )'];
        return $faturamento;
    }
    
    //calcula quando a pessoa faturou com determinado produto em um tempo
    //pode ser usado para ver qua item da mais faturamento, por exemplo
    function faturamentoDeterminadoProduto($nomeProduto, $idUsuario,  $dataInicial, $dataFinal){
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT SUM( `preco_venda` )  FROM vendas_itens INNER JOIN vendas 
            ON vendas_itens.id_venda = vendas.id_venda
            AND id_usuario = :idUsuario
            AND id_produto = ( SELECT id_produto FROM produto WHERE nome = :nomeProduto )
            AND `data`
            BETWEEN :dataInicial AND :dataFinal');
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal,
            'nomeProduto' =>$nomeProduto
        ));
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $faturamento = $resultado['SUM( `preco_venda` )'];
        return $faturamento;
    }
    
?>
