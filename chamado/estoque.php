<?php
//namespace estoque;

require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

/* 
 * Ao receber um GET com id de usuário, esta página deverá retornar a lista de itens no estoque do dado usuário.
 * Ao receber um POST, ela deverá inserir um novo item no estoque do usuário especificado ou modificar esse item, caso o id_produto já esteja especificado na requisição.
 * Ao receber um DELETE, ela vai deletar o item especificado.
 */

// ****************************************************************************
// Requisição POST: área de inserção/modificação de item no estoque
// ****************************************************************************
// Requisição POST envia 6 campos para esta página: id_usuario, id_produto, nome, quantidade, quantidade_tipo, custo.
// Se o campo id_estoque for igual a 0, o webService entenderá que o item deve ser novo e fará a inserção.
// Caso contrário, ele entenderá que se trata de uma atualização e fará as operações devidas para atualizar o item.
function insere($parametros){
    
    // Se a requisição contiver erros, a execução será interrompida e o cliente receberá um código 400
    //if(!isset($parametros['id_produto'])) return -1;
    
    // corrige a separação decimal do campo custo
    $parametros['custo'] = str_replace(",",".",$parametros['custo']);

    // primeiro insere na tabela de produto e resgata o id_produto escolhido pelo banco de dados
    $stmt = $GLOBALS['dbt']->prepare(
    'INSERT INTO produto (nome)
    VALUES (:nome)');
    $stmt->execute(array(
        'nome' => $parametros[nome]
    ));
    $id = $GLOBALS['dbt']->lastInsertId();

    // realiza a inserção equivalente na tabela de estoque
    $stmt = $GLOBALS['dbt']->prepare(
    'INSERT INTO estoque (id_produto, id_usuario, quantidade, quantidade_tipo, custo)
    VALUES (:id, :id_usuario, :quantidade, :quantidade_tipo, :custo)');
    $stmt->execute(array(
        'id' => $id,
        'id_usuario' => $parametros[id_usuario],
        'quantidade' => $parametros[quantidade],
        'quantidade_tipo' => $parametros[quantidade_tipo],
        'custo' => $parametros[custo]
    ));
    
    $resposta[mensagem] = 'Item inserido com sucesso';
    return $resposta;

}

function modifica($parametros){
    
    
    // Se a requisição contiver erros, a execução será interrompida e o cliente receberá um código 400
    if(!isset($parametros['id_produto'])) return -1;
    
    // corrige a separação decimal do campo custo
    $parametros['custo'] = str_replace(",",".",$parametros['custo']);
    
    // recupera os dados atuais sobre o item que será modificado
    $stmt = $GLOBALS['dbt']->prepare(
        'SELECT * 
        FROM estoque 
        WHERE id_produto = :id_produto');
    $stmt->execute(array(
        'id_produto' => $parametros[id_produto]
    ));
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // calcula os novos valores de quantidade de preço por unidade
    $resultado['custo'] = ($resultado['quantidade']*$resultado['custo']+$parametros['quantidade']*$parametros['custo'])/($parametros['quantidade'] + $resultado['quantidade']);
    $resultado['quantidade'] += $parametros['quantidade'];
    
    // atualiza as informações de estoque no banco de dados
    $stmt = $GLOBALS['dbt']->prepare(
        'UPDATE estoque 
        SET quantidade = :quantidade,
            quantidade_tipo = :quantidade_tipo,
            custo = :custo
        WHERE id_produto = :id_produto');
    $stmt->execute(array(
        'quantidade' => $resultado[quantidade],
        'quantidade_tipo' => $parametros[quantidade_tipo],
        'custo' => $resultado[custo],
        'id_produto' => $parametros[id_produto]
    ));
    
    // como o nome fantasia da ficha pode ter sido modificado também, atualiza a entrada na tabela de produto também
    $stmt = $GLOBALS['dbt']->prepare(
        'UPDATE produto 
        SET nome = :nome
        WHERE id_produto = :id_produto');
    $stmt->execute(array(
        'nome' => $parametros[nome],
        'id_produto' => $parametros[id_produto]
    ));

    $resposta[mensagem] = 'Item atualizado com sucesso';
    return $resposta;    
}

// ****************************************************************************
// Requisição DELETE: área que deleta item no estoque
// ****************************************************************************
// Requisição DELETE envia 2 campos para esta página via URI (acessível pelo array _GET): id_usuario, id_produto.
function deleta(){
    
    // Se houver um erro na requisição, retorna BAD REQUEST
    if(!isset($_GET['id_usuario']) || $_GET['id_usuario']==0) requisicao_incorreta();
    
    // Se houver algum erro na requisição, encerra o programa
    if(! ( isset($_GET['id_produto']) && $_GET['id_produto']!=0 && isset($_GET['id_produto']) && $_GET['id_produto'] != 0 )) requisicao_incorreta();
    
    
    // o comando a seguir elimina os dados tanto na tabela produto, quanto na tabela estoque
    mysql_query("DELETE FROM produto
                WHERE 
                id_produto='".$_GET['id_produto']."'
                ") or
                die("Erro ao deletar dados.");
    
}


// ****************************************************************************
// Requisição GET: área que prepara a lista de itens de estoque do usuário determinado
// ****************************************************************************
// Exemplo de requisição /api/estoque.php?id_usuario=14
function lista($parametros){
    
    // Ve se o valor recebido é valido e recupera o id do usuário
    $stmt = $GLOBALS['dbt']->prepare(
        'SELECT * 
        FROM produto INNER JOIN estoque ON produto.id_produto = estoque.id_produto 
        WHERE id_usuario = :id_usuario');
    $stmt->execute(array(
        'id_usuario' => $parametros[id_usuario]
    ));
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // troca o separador decimal do campo de custo de ponto por vírgula
    for($i = 0; $i < sizeof($resultado); $i++){
        $resultado[$i]['custo'] = str_replace(".",",",$resultado[$i]['custo']);
    }
    
    return $resultado;
    
}

// ********************************************************
//------------------FUNÇÕES VALIDADORAS------------------\\
// ********************************************************

// Função que valida os dados da inserção/modificação, sendos estes em especifico Quantidade e Custo 
function validaInsercao(){
    
    $erro = FALSE;
    
    foreach ($_POST as $chave => $valor) {
        // Remove todas as tags HTML
    	// Remove os espaços em branco do valor
    	$$chave = trim(strip_tags($valor));
    	
    	// Verifica se tem algum valor nulo
    	if (empty($valor)) {
    	    $erro = TRUE;
    	}
    }
    
    // Verifica se $quatidade realmente existe e se é um número. 
    if (!isset($_POST['quantidade']) || !is_numeric($_POST['quantidade']) || !maiorIgualZero($_POST['quantidade'])) {
        $erro = TRUE;
    }
    
    // Verifica se $precoUnidade realmente existe e se esta no formato ideal. 
    if (!isset($_POST['custo']) || !formatoReal($_POST['custo']) || !maiorIgualZero($_POST['custo'])) {
    	$erro = TRUE;
    } else {
        $_POST['custo'] = str_replace(",",".", $_POST['custo']);
    }
    
    return $erro;
    
}

// ********************************************************




// ********************************************************
//------------------FUNÇÕES AUXILIARES------------------\\
// ********************************************************

function maiorIgualZero($valor){
    if($valor >= 0){
        return true;
    } return false;
}

// Função que confirma de existem 2 digitos após a virgula (para verificar se o formato do dinheiro está correto)
function formatoReal($valor){
    list($whole, $decimal) = explode(',', $valor);
    
    $tamanhoDecimal = strlen((string)$decimal);
    
    if($tamanhoDecimal == 2){
        return true;
    } return false;
}

function queryParaArray($query){
    $aaux = array();
    while($r = mysql_fetch_assoc($query)) {
        $aaux[] = $r;
    }
    return $aaux;
}

// ********************************************************
                
?>