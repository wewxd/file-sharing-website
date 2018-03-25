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
        <script>
            $(document).ready(()=>{
                $('#upload').on('click', ()=>{
                    $('#inputFile').click()
                })
                $('#api').on('click', ()=>{
                    window.location.href="apiinfo.php"
                })
                $('#uploads').on('click', ()=>{
                    window.location.href="uploads.html"
                })
                $('#logout').on('click', ()=>{
                    document.cookie="apikey=;expires=Thu, 01 Jan 1970 00:00:00 UTC;"
                    window.location.reload()
                })
                $('#inputFile').on('change', ()=>{
                    $.ajax({
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        url: 'api/upload.php',
                        data: new FormData($('#hiddenForm')[0]),
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        beforeSend: ()=>$('#uploadedUrls').append('<span class="tmp gr">Uploading...</span>'),
                        success: data=>{
                            data=JSON.parse(data)
                            str=data.success===true?'<a class="gr" href="'+data.url+'">'+data.url+'</a></br>':data.msg+'<br>'
                            $('.tmp').remove()
                            $('#uploadedUrls').append(str)
                        },
                        error: e=>{alert(e)}
                    })
                })
                $('#hiddenForm').hide()
            })
        </script>
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
            <div class="inHome bigger"><div class="margintop"><a class="gr" href="contact.html">infos/contact idk</a></div></div>
        </div>
        <form id="hiddenForm"><input type="file" name="file" id="inputFile"></form>
    </body>
</html>
