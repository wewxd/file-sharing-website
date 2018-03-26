<?php
require_once './require/dblogin.php';
require_once './require/cookieLogin.php';
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
                        $.post('require/resetPwd.php', $('#form').serialize(), data=>{
                            if(data.success===true){
                                alert('Password updated')
                            }else{
                                alert('Your password could not be updated: '+data.msg)
                            }
                        })
                    }
                }

                $('.size').each(function(){$(this).html(getSizeStr($(this).html()))})

                function getSizeStr(size){
                    let sizes=['Bytes', 'KB', 'MB', 'GB']
                    if(size==0) return '0 bytes'
                    let i=parseInt(Math.floor(Math.log(size)/Math.log(1024)))
                    if(i===0) return (size/Math.pow(1024, i)) + '' + sizes[i]
                    return (size/Math.pow(1024, i)).toFixed(1) + '' + sizes[i]
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
                Just save it, fill in your API key and import it.<br><br>
                Upload limit: <span class="size"><?php echo $user['maxSize']; ?></span><br>
                Current size: <span class="size"><?php echo $user['actSize']; ?></span><br>
                File count: <?php echo $user['fileCount']; ?><br>
                Including deleted: <?php echo $user['fileCountWDel']; ?>
                <div class="btnWrapper"><div class="button" id="reset">Reset Key</div></div>
                <div class="btnWrapper"><div class="button" id="pwdButton">Change password</div></div>
                <form id="form" action="require/resetPwd.php" method="post">
                    <div class="paddingtop formContainer">
                        <div class="inForm">Old password</div>
                        <div class="inForm bigger"><input type="password" class="blackInput" name="old"></div>
                        <div class="inForm">New password</div>
                        <div class="inForm bigger"><input type="password" class="blackInput" id="pwd" name="new"></div>
                        <div class="inForm">Confirm  password</div>
                        <div class="inForm bigger"><input type="password" class="blackInput" id="pwdc"></div>
                    </div>
                    <div class="btnWrapper"><div id="submit" class="button">Submit</div></div>
                </form>
            </div>
        </div>
    </body>
</html>
