<?php 
if(empty($_COOKIE['apikey'])){
    require_once './require/login.html';
}else{
    require_once './require/home.php';
}
?>
