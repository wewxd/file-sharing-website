<?php
header('Content-Type: application/json');
require_once '../require/dblogin.php';

// check if user is allowed to upload
if(!empty($_POST['key'])){
    $key=$_POST['key'];
}else if(!empty($_COOKIE['apikey'])){
    $key=$_COOKIE['apikey'];
}else{
    die('{"success": false, "msg": "Please provide an API key"}');
}
$file=reset($_FILES);
if(empty($file['size'])){
    die('{"success": false, "msg": "Please provide a file"}');
}

$user=$db->prepare('SELECT * FROM users WHERE apikey=?');
$user->execute([$key]);
$user=$user->fetch();
if(empty($user)){
    die('{"success": false, "msg": "wrong API key"}');
}
if($user['allowed']==0){
    die('{"success": false, "msg": "This user is not allowed to upload"}');
}
if($file['size']>$user['maxSize']){
    die('{"success": false, "msg": "File too big"}');
}


// Hash the file to prevent uploading the same file multiple times, and generate a file name
// If an identical file exists, a new link is generated but points to the same file
// also find file type
$fileType=mime_content_type($file['tmp_name']);
$hash=hash_file('md5', $file['tmp_name']);
if(strrpos($file['name'], '.')==false||strlen($file['name'])-strrpos($file['name'], '.')>7){
    do{
        $filename=substr(str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN'), 0, 5);
    }while(file_exists($uploadPath.$filename));
}else{
    do{
        $filename=substr(str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN'), 0, 5).substr($file['name'], strrpos($file['name'], '.'));
    }while(file_exists($uploadPath.$filename));
}
$qExists=$db->prepare('SELECT id, path FROM files WHERE hash=? AND deleted=0');
$qExists->execute([$hash]);
$qExists=$qExists->fetch();
if(empty($qExists)){
    move_uploaded_file($file['tmp_name'], $uploadPath.$filename);
}else{
    link($qExists['path'], $uploadPath.$filename);
}

// Update the database
$user['fileCount']++;
$user['fileCountWDel']++;
$user['actSize']+=$file['size'];
$qf=$db->prepare('INSERT INTO files(name, type, size, path, hash, id_user) VALUES (?,?,?,?,?,?)');
$qu=$db->prepare('UPDATE users SET fileCount=?, fileCountWDel=?, actSize=? WHERE id=?');
$qf->execute([$file['name'], $fileType, $file['size'], $uploadPath.$filename, $hash, $user['id']]);
$qu->execute([$user['fileCount'], $user['fileCountWDel'], $user['actSize'], $user['id']]);

// Delete old files if the user has reached his upload limit
if($user['actSize']>$user['maxSize']){
    $userFiles=$db->prepare('SELECT id, size, path FROM files WHERE id_user=? AND deleted=0 ORDER BY date');
    $userFiles->execute([$user['id']]);
    $userFiles=$userFiles->fetchAll();
    $i=0;
    while($user['actSize']>$user['maxSize']){
        unlink($userFiles[$i]['path']);
        $delReq=$db->prepare('UPDATE files SET deleted=1 WHERE id=?');
        $delReq->execute([$userFiles[$i]['id']]);
        $user['actSize']-=$userFiles[$i]['size'];
        $user['fileCount']--;
        $i++;
    }
    $updateUsr=$db->prepare('UPDATE users SET actSize=?, fileCount=? WHERE id=?');
    $updateUsr->execute([$user['actSize'], $user['fileCount'], $user['id']]);
}

// Return the URL
echo '{"success":true,"url":"'.$conf['url'].$filename.'"}';
?>
