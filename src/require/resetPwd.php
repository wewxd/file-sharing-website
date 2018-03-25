<?php
require_once './dblogin.php';
require_once './cookieLogin.php';
if(password_verify($_POST['old'], $user['pwd'])){
    $user['pwd']=password_hash($_POST['new'], PASSWORD_DEFAULT);
    $q=$db->prepare('UPDATE users SET pwd=? WHERE id=?');
    $q->execute([$user['pwd'], $user['id']]);
    echo '{"success": true}';
}else{
    echo'{"success": false, "msg": "Wrong password"}';
}
?>
