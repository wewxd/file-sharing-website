<?php
header('Content-Type: application/json');
require_once '../require/dblogin.php';
require_once '../require/cookieLogin.php';
$str=str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN');
$user['apikey']=substr(password_hash($user['apikey'].$str, PASSWORD_DEFAULT), 20);
$q=$db->prepare('UPDATE users SET apikey=? WHERE id=?');
$q->execute([$user['apikey'], $user['id']]);
setcookie('apikey', $user['apikey'], 0, '/', 'fuckmy.cat', true);
echo '{"success": true, "key": "'.$user['apikey'].'"}';
?>