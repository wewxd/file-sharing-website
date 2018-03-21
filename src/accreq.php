<?php
if(file_exists(getenv('HOME').'/fmc.conf')){
    $conf=json_decode(file_get_contents(getenv('HOME').'/fmc.conf'), true);
}else{
    die('No config file found at '.getenv('HOME').'/fmc.conf');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css" />
        <title>New account</title>
        <script>
            $(document).ready(()=>{
                $('#submit').on('click', ()=>{
                    submitForm()
                })
                $('input').keyup(event=>{
                    if(event.keyCode===13)submitForm()
                })
                function submitForm(){
                    if($('#pwd').val()===''||$('#name').val()===''||$('#email').val()===''){alert('Some fields are empty')}
                    else if($('#pwd').val()!==$('#pwdc').val()){
                        alert('passwords do not match')
                    }else{$('#form').submit()}
                }
            })
        </script>
    </head>
    <div class="wrapper">
        <div class="sometxt">
            hi<br><br>
            Here's a form you can fill if you want an account<br><br>
            You can upload up to <?php echo $conf['maxSizeTxt']; ?>. After that, your old files get deleted as you upload new files.
        </div>
        <hr>
        <form id="form" action="api/newacc.php" method="post">
            <div class="formContainer paddingtop">
                <div class="inForm">name</div>
                <div class="inForm bigger"><input type="text" id="name" name="name"></div>
                <div class="inForm">email</div>
                <div class="inForm bigger"><input type="text" id="email" name="email"></div>
                <div class="inForm">password</div>
                <div class="inForm bigger"><input type="password" id="pwd" name="pwd"></div>
                <div class="inForm">confirm</div>
                <div class="inForm bigger"><input type="password" id="pwdc"></div>
                <div class="inForm"><div id="submit" class="button">Submit</div></div>
            </div>
        </form>
    </div>
</html>
