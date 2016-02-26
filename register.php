<?php
require_once 'include/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $db->prepare('INSERT INTO users (name, email, created) ' .
        'VALUES (:name, :email, NOW())');
    $stmt->execute(array(
        'name' => $_POST['requester_name'],
        'email' => $_POST['requester_email']
    ));
    $id = $db->lastInsertId();

    $key = $store->updateConsumer($_POST, $id, true);
    $c = $store->getConsumer($key, $id);
?>
  <p><strong>Save these values!</strong></p>
  <p>Consumer key: <strong><?=$c['consumer_key']; ?></strong></p>
  <p>Consumer secret: <strong><?=$c['consumer_secret']; ?></strong></p>
<?php
}
else {
?>
  <form method="post"
   action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
   <fieldset>
    <legend>Register</legend>
    <div>
     <label for="requester_name">Name</label>
     <input type="text" id="requester_name" name="requester_name">
    </div>
    <div>
     <label for="requester_email">Email</label>
     <input type="text" id="requester_email" name="requester_email">
    </div>
    <div>
     <label for="application_uri">URI</label>
     <input type="text" id="application_uri" name="application_uri">
    </div>
    <div>
     <label for="callback_uri">Callback URI</label>
     <input type="text" id="callback_uri" name="callback_uri">
    </div>
   </fieldset>
   <input type="submit" value="Register">
  </form>
<?php
}
?>
 </body>
</html>
