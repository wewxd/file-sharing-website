<?php 
// Check if an account already exists with the same username/email
require_once '../require/dblogin.php';
if($conf['allowNewAccounts']==false){
    die("New accounts aren't given away right now");
}
if(!preg_match('/\w*@\w*\.\w*/', $_POST['email'])){
    die('fix your email address ok');
}

$q=$db->prepare('SELECT name FROM users WHERE name=?');
$q->execute([$_POST['name']]);
if(!empty($q->fetch())){
    die('Username already taken');
}
$q=$db->prepare('SELECT mail FROM users WHERE mail=?');
$q->execute([$_POST['email']]);
if(!empty($q->fetch())){
    die('email address already taken');
}

// Update the database
$q=$db->prepare('INSERT INTO users (name, mail, maxSize, fileCount, actSize, pwd, apikey, allowed) VALUES (?, ?, ?, 0, 0, ?, ?, 1)');
$pwd=password_hash($_POST['pwd'], PASSWORD_DEFAULT);
$str=str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN');
$key=substr(password_hash($pwd.$str, PASSWORD_DEFAULT), 20);
$q->execute([$_POST['name'], $_POST['email'], $conf['newAccountMaxSize'], $pwd, $key]);
echo "Account created";
?>
