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
    die('{"success": false, "msg": "File too large"}');
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
$qu->execute([$user['fileCount'], $user['fileCountWDel'], $user['actSize'], $user['id']]);

// Delete old files if the user has reached his upload limit
$deleteImportant=0;
while($user['actSize']>$user['maxSize']){
    $userFiles=$db->prepare('SELECT id, size, path FROM files WHERE id_user=? AND deleted=0 AND important=? ORDER BY date LIMIT 100');
    $userFiles->execute([$user['id'], $deleteImportant]);
    $userFiles=$userFiles->fetchAll();
    foreach($userFiles as $row){
        if($user['actSize']<$user['maxSize']){ break; }
        unlink($row['path']);
        $delReq=$db->prepare('UPDATE files SET deleted=1 WHERE id=?');
        $delReq->execute([$row['id']]);
        $user['actSize']-=$row['size'];
        $user['fileCount']--;
    }
    $updateUsr=$db->prepare('UPDATE users SET actSize=?, fileCount=? WHERE id=?');
    $updateUsr->execute([$user['actSize'], $user['fileCount'], $user['id']]);
    if(count($userFiles)==0) $deleteImportant=1;
}

// The uploaded file isn't marked as "important" by default. If it is added in the database before the old files get deleted,
// it will be deleted if all the other files are "important" and the user reaches his upload limit.
$qf->execute([$file['name'], $fileType, $file['size'], $uploadPath.$filename, $hash, $user['id']]);

// Return the URL
echo '{"success":true,"url":"'.$conf['url'].$filename.'"}';
?>
