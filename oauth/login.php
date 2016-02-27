<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check if the login information is valid and get the user's ID
    $stmt = $db->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute(array(
        'email' => $_POST['requester_email']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo '<p><strong>Incorrect login</strong></p>';
        die;
    }
    $id = $row['id'];
    $stmt->closeCursor();

    // Check if there is a valid request token in the current request.
    // This returns an array with the consumer key, consumer secret, token,
    // token secret and token type.
    $rs = $server->authorizeVerify();
    
    // See if the user clicked the 'allow' submit button (or whatever you choose)
    /*$authorized = array_key_exists('allow', $_POST);
    
    print_r($_POST);
    echo $authorized;
    var_dump($authorized);
    die();
    */
    
    // Set the request token to be authorized or not authorized
    // When there was a oauth_callback then this will redirect to the consumer
    //$server->authorizeFinish($authorized, $id);
    $server->authorizeFinish(true, $id);
}
else {
?>
  <form method="post">
   <fieldset>
    <legend>Login</legend>
    <div>
     <label for="requester_email">Email</label>
     <input type="text" id="requester_email" name="requester_email">
    </div>
   </fieldset>
   <input type="submit" value="Login">
  </form>
<?php
}
?>
 </body>
</html>
