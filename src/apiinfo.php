<?php
require './require/dblogin.php';
require './require/cookieLogin.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        <title>API</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(()=>{
                $('#reset').on('click', ()=>{
                    $.post('require/resetKey.php', data=>{
                        data=JSON.parse(data)
                        if(data.success===true){
                            $('#key').empty().text(data.key)
                        }else{alert(data.msg)}
                    })
                })
                $('#pwdButton').on('click', ()=>{
                    $('#pwdButton').hide()
                    $('#form').show()
                })
                $('#submit').on('click', ()=>{submitForm()})
                $('input').keyup(event=>{if(event.keyCode===13)submitForm()})
                $('#form').hide()
                function submitForm(){
                    if($('#pwd').val()===''||$('#pwdc')===''){
                        alert('Please enter a new password')
                    }else if($('#pwd').val()!==$('#pwdc').val()){
                        alert('Passwords do not match')
                    }else{
                        $.post('require/resetPwd.php', data=>{
                            data=JSON.parse(data)
                            if(data.success===true){
                                alert('Password updated')
                            }else{
                                alert('Your password could not be updated: '+data)
                            }
                        })
                    }
                }
            })
        </script>
    </head>
    <body>
        <div class="bigwrapper">
            <div class="inBigWrapper">
                Your API key is:<br><span id="key"><?php echo $user['apikey']; ?></span><br><br>
                Here are the configuration files for
                <a class="gr" href="examples/fuckmy.cat.sxcu">ShareX</a> or <a class="gr" href="examples/fuckmy.cat.uploader">KShare</a>.<br>
                Just save it, fill in your API key and import it.
                <div class="btnWrapper"><div class="button" id="reset">Reset Key</div></div>
                <div class="btnWrapper"><div class="button" id="pwdButton">Change password</div></div>
                <form id="form" action="require/resetPwd.php" method="post">
                    <div class="paddingtop formContainer">
                        <div class="inForm">Old password</div>
                        <div class="inForm bigger"><input type="password" name="old"></div>
                        <div class="inForm">New password</div>
                        <div class="inForm bigger"><input type="password" id="pwd" name="new"></div>
                        <div class="inForm">Confirm  password</div>
                        <div class="inForm bigger"><input type="password" id="pwdc"></div>
                    </div>
                    <div class="btnWrapper"><div id="submit" class="button">Submit</div></div>
                </form>
            </div>
        </div>
    </body>
</html>
