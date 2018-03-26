<?php
header('Content-Type: application/json');
require_once'../require/dblogin.php';
if(empty($_POST['file'])){
}
$file=$db->prepare('SELECT id,name,type,size,path,date,deleted,hash FROM files WHERE id=?');
$file->execute([$_POST['file']]);
$file=$file->fetch();
if(empty($file['name'])){
    die('{"success": false, "msg": "This file does not exist"}');
}
$returned['id']=$file['id'];
$returned['name']=$file['name'];
$returned['type']=$file['type'];
$returned['size']=$file['size'];
$returned['date']=$file['date'];
$returned['hash']=$file['hash'];
$returned['url']=$conf['url'].basename($file['path']);
echo json_encode($returned);
?>
