<?php
require_once './require/dblogin.php';
require_once './require/cookieLogin.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="scripts.js"></script>
        <title>fuck my cat</title>
    </head>
    <body>
        <div class="wrapper">
            <h2 class="nomargintop marginbottom">Hi</h2>
            <div class="homeContainer">
                <div class="inHome"><div id="upload" class="button"><b>Click here to upload</b></div></div>
                <div class="inHome small" id="uploadedUrls"></div>
                <div class="inHome"><div id="uploads" class="button">Your uploads</div></div>
                <div class="inHome"><div id="api" class="button">API info</div></div>
                <div class="inHome"><div id="logout" class="button">Log out</div></div>
            </div>
            <div class="inHome bigger"><div class="margintop"><a class="gr" href="contact.html">info/contact idk</a></div></div>
        </div>
        <form id="hiddenForm"><input type="file" name="file" id="inputFile"></form>
    </body>
</html>
