<?php
require_once './require/dblogin.php';
require_once './require/cookieLogin.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>API</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="scripts.js"></script>
    </head>
    <body>
        <div class="bigwrapper">
            <div class="inBigWrapper">
                Your API key is:<br><span id="key"><?php echo $user['apikey']; ?></span><br><br>
                Here are the configuration files for
                <a class="gr" href="examples/fuckmy.cat.sxcu">ShareX</a> or <a class="gr" href="examples/fuckmy.cat.uploader">KShare</a>.<br>
                Just save it, fill in your API key and import it.<br><br>
                Upload limit: <span class="size"><?php echo $user['maxSize']; ?></span><br>
                Current size: <span class="size"><?php echo $user['actSize']; ?></span><br>
                File count: <?php echo $user['fileCount']; ?><br>
                Including deleted: <?php echo $user['fileCountWDel']; ?>
                <div class="btnWrapper"><div class="button" id="reset">Reset Key</div></div>
                <div class="btnWrapper"><div class="button" id="pwdButton">Change password</div></div>
                <form id="form" action="post/resetPwd.php" method="post">
                    <div class="paddingtop formContainer">
                        <div class="inForm">Old password</div>
                        <div class="inForm bigger"><input type="password" class="pwdInput blackInput" name="old"></div>
                        <div class="inForm">New password</div>
                        <div class="inForm bigger"><input type="password" class="pwdInput blackInput" id="pwd" name="new"></div>
                        <div class="inForm">Confirm  password</div>
                        <div class="inForm bigger"><input type="password" class="pwdInput blackInput" id="pwdc"></div>
                    </div>
                    <div class="btnWrapper"><div id="resetPwd" class="button">Submit</div></div>
                </form>
                <a href="/">Back to index/<a>
            </div>
        </div>
    </body>
</html>
