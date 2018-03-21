<?php 
if(empty($_COOKIE['apikey'])){
    require './require/login.html';
}else{
    require './require/home.php';
}
?>
