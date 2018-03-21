<?php
if(empty($_COOKIE['apikey'])) die('<script>window.location.href="'.$conf['baseurl'].'"</script>');
$user=$db->prepare('SELECT id, name, maxSize, fileCount, fileCountWDel, actSize, allowed, apikey FROM users WHERE apikey=?');
$user->execute([$_COOKIE['apikey']]);
$user=$user->fetch();
if(empty($user['name'])){
    setcookie('apikey', '', time()-3600, '/');
    die('<script>window.location.href="'.$conf['baseurl'].'"</script>');
}
?>
