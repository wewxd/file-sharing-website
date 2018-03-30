<?php
header('Content-Type: application/json');
require_once '../require/dblogin.php';
require_once '../require/cookieLogin.php';
if(empty($_POST['file'])){
    die('{"success": false, "msg": "Please select a file"}');
}
$file=$db->prepare('SELECT id, deleted, important, id_user FROM files WHERE id=?');
$file->execute([$_POST['file']]);
$file=$file->fetch();
if($user['id']!=$file['id_user']){
    die('{"success": false, "msg": "This file does not belong to you"}');
}
$file['important']=$file['important']==1?0:1;
$update=$db->prepare('UPDATE files SET important=? WHERE id=?');
$update->execute([$file['important'], $file['id']]);
echo '{"success": true, "important": '.$file['important'].'}';
?>
