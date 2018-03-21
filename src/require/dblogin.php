<?php 
if(file_exists(getenv('HOME').'/fmc.conf')){
    $conf=json_decode(file_get_contents(getenv('HOME').'/fmc.conf'), true);
}else{
    die('No config file found at '.getenv('HOME').'/fmc.conf');
}
$uploadPath=$conf['path'];
$u=$conf['usr'];
$p=$conf['pwd'];
try{$db=new PDO('mysql:host=localhost;dbname=fmc',$u,$p);}
catch(PDOException $e){die($e->getMessage());}
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
