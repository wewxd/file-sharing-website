$(document).ready(()=>{
    // UPLOADS

    // Initialisation
    if(window.location.href.includes('uploads')){
        let filesPerPage
        let total
        let page=1
        $('#infoWrapper').hide()
        update(page)
    }

    // Make the images clickable as links
    $(document).on('click', '.uplImg', function(){
        location.href=$(this).parent().find('a').attr('href')
    })
    // Show file info
    $(document).on('click', '.info', function(){
        $.post('api/fileinfo.php', {file: $(this).closest('.upl').attr('id')}, data=>{
            $('#filename').html(data.name)
            $('#fileimportant').html(data.important==1?'Yes':'No')
            $('#isImp').html(data.important==1?'not important':'important')
            $('#filetype').html(data.type)
            $('#filesize').html(getSizeStr(data.size))
            $('#filedate').html(data.date)
            $('#fileurl a').html(data.url).attr('href', data.url)
            $('#fileId').val(data.id)
            $('#infoWrapper').show()
            $('.uploadsWrapper').css('filter', 'blur(5px)')
        })
    })
    $(document).on('click', '.ok', function(){
        $('.uploadsWrapper').css('filter', '')
        $('.infoWrapper').hide()
    })
    // Delete a file
    $(document).on('click', '.delete', function(){
        $.post('api/delete.php', {file: $('#fileId').val()}, data=>{
            if(data.success===false){
                alert(data.msg)
            }else{
                $('#'+$('#fileId').val()).closest('.upl').remove()
                $('.uploadsWrapper').css('filter', '')
                $('#infoWrapper').hide()
            }
        })
    })
    //Mark a file as important
    $('#makeImp').on('click', ()=>{
        $.post('post/makeImportant.php', {file: $('#fileId').val()}, data=>{
            if(data.success==false){
                alert(data.msg)
            }else{
                $('#fileimportant').html(data.important==1?'Yes':'No')
                $('#isImp').html(data.important==1?'not important':'important')
            }
        })
    })
    // Pagination: arrows
    $(document).on('click', '.clickable', function(){
        if($(this).attr('id')==='first') page=1
        if($(this).attr('id')==='prev') page=Math.max(1, page-filesPerPage)
        if($(this).attr('id')==='next') page=Math.min(total-filesPerPage+1, page+filesPerPage)
        if($(this).attr('id')==='last') page=total-filesPerPage+1
        update(page)
    })
    // Pagination: text input
    $('#pageInput').on('click', function(){
        $(this).val('')
    })
    $('#pageInput').keydown(function(event){
        if(event.key.match(/\D/)) event.preventDefault()
        if(event.keyCode===13){
            $(this).blur()
            if($(this).val()=='') $(this).val('1')
            page=$('#pageInput').val()
            page=Math.min(page, total-filesPerPage+1)
            page=Math.max(page, 1)
            update(page)
        }
    })

    // Reload the files
    function update(offset){
        $.post('post/getUploads.php', {offset: offset}, data=>{
            $('#st').empty().append(data.start)
            $('#end').empty().append(data.end)
            $('#total').empty().append(data.total)
            $('#pageInput').val(data.start)
            total=data.total
            $('#uploadsList').empty()
            filesPerPage=data.data.length
            data.data.forEach(upl=>{
                let str='<div class="upl" id="'+upl.id+'">'
                if(upl.type.startsWith('image')){
                    str+='<img class="uplImg" src="'+upl.url+'">'
                }
                str+=upl.name+'<div class="smolflex"><div>'
                str+='<a class="gr" href="'+upl.url+'">'+upl.newName+'</a></div>'
                str+='<div>-</div><div class="gr info">info</div></div>'
                $('#uploadsList').append(str)
            })
        })
    }


    // INDEX
    // Initialisation
    $('#hiddenForm').hide()
    // Buttons
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
    // File upload
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
                str=data.success===true?'<a class="gr" href="'+data.url+'">'+data.url+'</a></br>':data.msg+'<br>'
                $('.tmp').remove()
                $('#uploadedUrls').append(str)
            },
            error: e=>{alert(e)}
        })
    })


    // LOGIN
    $('.logInput').keyup(event=>{
        if(event.keyCode===13)submitLogin()
    })
    $('#login').on('click', ()=>{
        submitLogin()
    })
    function submitLogin(){
        $.post({
            url: 'post/login.php',
            data: $('#loginform').serialize(),
            success: data=>{
                data==='reload'?location.reload():alert(data)
            }
        })
    }


    // API INFO
    $('#reset').on('click', ()=>{
        $.post('post/resetKey.php', data=>{
            if(data.success===true){
                $('#key').empty().text(data.key)
            }else{alert(data.msg)}
        })
    })
    $('#pwdButton').on('click', ()=>{
        $('#pwdButton').hide()
        $('#form').show()
    })
    $('#resetPwd').on('click', ()=>{submitNewPwd()})
    $('.pwdInput').keyup(event=>{if(event.keyCode===13)submitNewPwd()})
    $('#form').hide()
    function submitNewPwd(){
        if($('#pwd').val()===''||$('#pwdc')===''){
            alert('Please enter a new password')
        }else if($('#pwd').val()!==$('#pwdc').val()){
            alert('Passwords do not match')
        }else{
            $.post('post/resetPwd.php', $('#form').serialize(), data=>{
                if(data.success===true){
                    alert('Password updated')
                }else{
                    alert('Your password could not be updated: '+data.msg)
                }
            })
        }
    }

    $('.size').each(function(){$(this).html(getSizeStr($(this).html()))})

    // GLOBAL
    function getSizeStr(size){
        let sizes=['Bytes', 'KB', 'MB', 'GB']
        if(size==0) return '0 bytes'
        let i=parseInt(Math.floor(Math.log(size)/Math.log(1024)))
        if(i===0) return (size/Math.pow(1024, i)) + '' + sizes[i]
        return (size/Math.pow(1024, i)).toFixed(1) + '' + sizes[i]
    }
})



