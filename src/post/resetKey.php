<?php
header('Content-Type: application/json');
require_once '../require/dblogin.php';
require_once '../require/cookieLogin.php';
$user['apikey']=preg_replace('/\W/', '', base64_encode(random_bytes(35)));
$q=$db->prepare('UPDATE users SET apikey=? WHERE id=?');
$q->execute([$user['apikey'], $user['id']]);
setcookie('apikey', $user['apikey'], 0, '/', null, true);
echo '{"success": true, "key": "'.$user['apikey'].'"}';
?>
