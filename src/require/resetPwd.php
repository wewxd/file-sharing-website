<?php
require './dblogin.php';
require './cookieLogin.php';
$user['pwd']=password_hash($user['apikey'], PASSWORD_DEFAULT);
$q=$db->prepare('UPDATE users SET pwd=? WHERE id=?');
if(password_verify($_POST['pwd'], $user['pwd']){
    $q->execute([$_POST['pwd'], $user['id']]);
    echo '{"success": true}';
}else{
    echo'{"success": false, "msg": "Wrong password"}';
}
?>
