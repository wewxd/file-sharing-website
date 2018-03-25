<?php
require_once './dblogin.php';
require_once './cookieLogin.php';
$str=str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN');
$user['apikey']=substr(password_hash($user['apikey'].$str, PASSWORD_DEFAULT), 7);
$q=$db->prepare('UPDATE users SET apikey=? WHERE id=?');
$q->execute([$user['apikey'], $user['id']]);
setcookie('apikey', $user['apikey'], 0, '/', 'fuckmy.cat', true);
echo '{"success": true, "key": "'.$user['apikey'].'"}';
?>
