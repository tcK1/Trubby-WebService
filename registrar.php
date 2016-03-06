<?php require_once 'include/common.php'; ?>
<!-- Compressed CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.0/foundation.min.css">
<!-- Compressed JavaScript -->
<script src="https://cdn.jsdelivr.net/foundation/6.2.0/foundation.min.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Registrar</title>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Confere se é um chamado para Registrar (if) ou Recuperar (else)
    if(strcasecmp($_POST['tipo'], 'Registrar') == 0){

        $stmt = $db->prepare('INSERT INTO users (name, email, created) ' .
        'VALUES (:name, :email, NOW())');
        $stmt->execute(array(
            'name' => $_POST['requester_name'],
            'email' => $_POST['requester_email']
        ));
        $id = $db->lastInsertId();
    
        $key = $store->updateConsumer($_POST, $id, true);
        $c = $store->getConsumer($key, $id);    
        
        // Printa as chaves na tela para o usuário
        ?>  <br><br>
            <p><strong>Suas chaves (Lembre-se de salva-las!):</strong></p>
            <p>Chave: <strong><?=$c['consumer_key']; ?></strong></p>
            <p>Segredo: <strong><?=$c['consumer_secret']; ?></strong></p> <?php

    } else {
        // Consulta a tabela de chaves
        $stmt = $db->prepare('SELECT * FROM oauth_server_registry WHERE osr_requester_name = :name AND osr_requester_email = :email');
        $stmt->execute(array(
            'name' => $_POST['requester_name'],
            'email' => $_POST['requester_email']
        ));
        
        // Retorna todas as linhas encontradas com aquela combinação de email e nome
        $consulta = $stmt->fetchAll();
        
        // Se encontrou imprime os valores na tela
        if(!empty($consulta)){

            ?>  <br><br>
                <p><strong>Suas chaves (Lembre-se de salva-las!):</strong></p>
                <p>Chave: <strong><?=$consulta[0]['osr_consumer_key']; ?></strong></p>
                <p>Segredo: <strong><?=$consulta[0]['osr_consumer_secret']; ?></strong></p> <?php
                
        } else { // Caso não tenha encontrado uma combinação
            ?> <br><br><p class="float-center"><strong>Essa combinação de Nome e Email não retornou nenhuma chave valida</strong></p> <?php
        }
    }
}
else {
?>
<div class="row">
    <div class="large-6 columns">
        <form method="post"
            action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <fieldset>
                <legend class="float-center"><br><b>Registrar</b></legend>
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="requester_name">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="requester_email">
                </div>
            </fieldset>
            <input class="success button float-center" type="submit" value="Registrar" name="tipo">
        </form>
    </div>
    <div class="large-6 columns">
        <form method="post"
            action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <fieldset>
                <legend class="float-center"><br><b>Recuperar</b></legend>
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="requester_name">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="requester_email">
                </div>
            </fieldset>
            <input class="button float-center" type="submit" value="Recuperar" name="tipo">
        </form>
    </div>
</div>
<?php
}
?>
</body>
</html>
