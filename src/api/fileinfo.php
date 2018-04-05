<?php
header('Content-Type: application/json');
require_once'../require/dblogin.php';
if(empty($_POST['file'])){
    die('{"success": false, "msg": "Please select a file"}');
}
$file=$db->prepare('SELECT id,name,type,size,newName,date,deleted,important,hash FROM files WHERE id=?');
$file->execute([$_POST['file']]);
$file=$file->fetch();
if(empty($file['name']) && $file['deleted']==0){
    die('{"success": false, "msg": "This file does not exist"}');
}
$returned['id']=$file['id'];
$returned['name']=$file['name'];
$returned['type']=$file['type'];
$returned['size']=$file['size'];
$returned['date']=$file['date'];
$returned['important']=$file['important'];
$returned['hash']=$file['hash'];
$returned['url']=$conf['url'].$file['newName'];
echo json_encode($returned);
?>
