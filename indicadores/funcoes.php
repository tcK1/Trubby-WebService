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
    function mediaPorVenda($idUsuario, $dataInicial, $dataFinal){
       
    $stmt = $GLOBALS['dbt']->prepare(
        'SELECT AVG(  `total` ) 
        FROM (
            SELECT SUM(  `preco_venda` ) AS total
            FROM  `vendas_itens` 
            INNER JOIN  `vendas` ON  `vendas_itens`.`id_venda` =  `vendas`.`id_venda` 
            AND  `id_usuario` = :idUsuario
            AND  `data` 
            BETWEEN  :dataInicial
            AND  :dataFinal
            GROUP BY  `vendas_itens`.`id_venda`
        )tabela');
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal,
        ));
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $media = $resultado['AVG(  `total` )'];
        return $media;
        
    }
    
    //calcular em toda a historia? ou da semana anterior? mes anterior? apenas nos dias que vendeu algo
    function mediaFaturamentoDiario($idUsuario, $dataInicial, $dataFinal){
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT AVG(  `somas` ) as media
            FROM  `vendas_dia` 
            WHERE DATE
            BETWEEN  :dataInicial
            AND  :dataFinal
            AND  `id_usuario` =:idUsuario');
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal
        ));
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
		$faturamento = $resultado['media'];
        return $faturamento;
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
    
    //funcao que retorna o quanto de cada item foi gasto em determinado tempo
    // retorna um arranjo contendo o nome e a quantidade gasto de cada item
    function estoqueGastoUsuario($idUsuario,  $dataInicial, $dataFinal){
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT estoque_gasto.id_produto, produto.nome, SUM(  `gasto` ) AS gasto
            FROM estoque_gasto
            INNER JOIN estoque ON estoque_gasto.`id_produto` = estoque.`id_produto` 
            INNER JOIN produto ON estoque_gasto.`id_produto` = produto.`id_produto` 
            AND estoque.id_usuario =:idUsuario
            AND  `data` 
            BETWEEN  :dataInicial
            AND  :dataFinal
            GROUP BY estoque_gasto.`id_produto` 
            ORDER BY SUM(`gasto`) DESC '
        );
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'dataInicial' =>$dataInicial,
            'dataFinal' =>$dataFinal
        ));
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    
    //ve o quanto foi gasto de estoque na semana anterior 
    //com base nisso, tenta prever quantos dias irao durar o estoque que você possui
    //retorna um arranjo contendo o nome do item e quantas semanas tem para ele acabar
    function previsaoFimEstoque($idUsuario){
        $dataAtual = date('y-m-d H:i:s');
        $semanaPassada = date('y-m-d H:i:s', strtotime('-1 week'));
        //ve quanto foi gasto de cada item em uma semana
        $gastosEstoque = estoqueGastoUsuario($idUsuario,$semanaPassada,$dataAtual);
        //ve o quanto tem
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT  `quantidade` 
            FROM estoque
            WHERE  `id_produto` =:idProduto'
        );
        //echo count($gastosEstoque);
        $aux = 0;
        $estoque = array();;
        while($item = array_shift($gastosEstoque)){
            $stmt->execute(array(
                'idProduto' => $item['id_produto']
            ));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $restante = $resultado['quantidade']/$item['gasto'];
            array_push($estoque,$item['nome'],$restante);
        }
        return $estoque;
    }
    
    //ve qual é a combinação de produtos mais vendidos
    function vendaCasada($id_usuario){
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT tst.id_venda, vendas_itens.id_produto, tst.contador
            FROM vendas_itens
            INNER JOIN (
            
            SELECT vendas_itens.id_venda, COUNT( * ) AS contador
            FROM vendas_itens
            INNER JOIN vendas ON vendas.`id_venda` = vendas_itens.`id_venda`
            AND id_usuario =id_usuario
            GROUP BY vendas_itens.`id_venda`
            HAVING COUNT( vendas_itens.`id_venda` ) >1
            ) AS tst ON vendas_itens.id_venda = tst.id_venda
            ORDER BY tst.contador DESC '
        );
        $stmt->execute(array(
            'idUsuario' => $idUsuario
        ));
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    
    //funcao para calcular o faturamento medio até um determinado horário
    function mediaFaturamentoHorario($idUsuario, $horaInicial, $horaFinal){
        $stmt = $GLOBALS['dbt']->prepare(
            'SELECT AVG(  `somas` ) as media
            FROM  `vendas_dia` 
            WHERE  `TIME` 
            BETWEEN  :horaInicial
            AND  :horaFinal
            AND  `id_usuario` =:idUsuario');
        $stmt->execute(array(
            'idUsuario' => $idUsuario,
            'horaInicial' =>$horaInicial,
            'horaFinal' =>$horaFinal
        ));
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $faturamento = $resultado['media'];
        return $faturamento;
    }
?>
