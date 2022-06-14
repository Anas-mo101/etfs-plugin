jQuery( document ).ready( function( $ ) { 
    let toggle_and_not_saved = false;
    $('.cancel-file-button').on('click', cancel_file);
    $('#ETFs-Pre-auto').on('click', toggle_switch_text);
    $('.cancel-button').on('click', cancel_onclick);
    $('.edit-button').on('click', edit_onclick);
    cancel_onclick(false);
    cancel_file();

    $('.edit-file-button').on('click', () => {
        $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', false);
        $(".edit-file-button").hide();
        $(".scan-dir-button").show();
        $(".update-files-button").show();
        $(".cancel-file-button").show();
    });

    function cancel_file(){
        $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', true);
        $(".edit-file-button").show();
        $(".scan-dir-button").hide();
        $(".update-files-button").hide();
        $(".cancel-file-button").hide();
    }

    function toggle_switch_text(){
        if($("#ETFs-Pre-auto").is(":checked") == true){
            $("#ETF-Pre-toggle-state-text").html("on");
        }else{
            $("#ETF-Pre-toggle-state-text").html("off");
        }
        toggle_and_not_saved = true;
    }

    function toggle_switch_reset(){
        if(toggle_and_not_saved == true){
            if($("#ETFs-Pre-auto").is(":checked") == true){
                $("#ETF-Pre-toggle-state-text").html("off");
                $("#ETFs-Pre-auto").prop('checked', false);
            }else{
                $("#ETF-Pre-toggle-state-text").html("on");
                $("#ETFs-Pre-auto").prop('checked', true);
            }
        }
    }

    function edit_onclick(){
        $('.save-button, .cancel-button').show();
        $("#ETFs-Pre-auto").prop('disabled', false);
        $('.edit-button').hide();
        $("input").prop('disabled', false);
        $("select").prop("disabled", false);
    }

    function cancel_onclick(flag = true){
        $("#ETFs-Pre-auto, #ETFs-Pre-pass, #ETFs-Pre-user, #ETFs-Pre-port, #ETFs-Pre-host, #ETFs-Pre-freq").prop('disabled', true);
        $('.save-button, .cancel-button').hide();
        $('.edit-button').show();
        if(flag) toggle_switch_reset();
        toggle_and_not_saved = false;
    }
    // ajax requests

    $('.save-button').on('click', () => {
        $("#ETFs-Pre-loadinganimation").css('display', 'inline-block');
        var data = { 
            action: 'etfconfig',
            host: $("#ETFs-Pre-host").val(),
            state: $("#ETFs-Pre-auto").is(":checked"),
            user: $("#ETFs-Pre-user").val(),
            pass: $("#ETFs-Pre-pass").val(),
            port: $("#ETFs-Pre-port").val(),
            freq: $("#ETFs-Pre-freq").val(),
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response);
                cancel_onclick(false);
                $("#ETFs-Pre-loadinganimation").css('display', 'none');
            }
        })
        .fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation").css('display', 'none');
        });
    });

    $('.scan-dir-button').on('click', () => {
        $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'inline-block');
        $.ajax({
            type: "GET",
            url: ajaxurl,
            data: {  action: 'scansftpdir' },
            cache: false,
            success: function( response ) {
                console.log(response);
                document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML = '';
                if(Array.isArray(response.files)){
                    response.files.forEach(file => {
                        let ext = file.split('.').pop();
                        const x = `<div class="tile form" draggable="true" ondragstart="event.dataTransfer.setData('text', '${file}')"> <i class="mdi mdi-file-document"></i> <p id="${file}" style="word-break: break-all;">${file}</p> <p>${ext} file</p> </div>`;
                        document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML = document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML + x;
                    });
                }

                $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', true);
                $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            }
        })
        .fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
        });
    });

    $('.update-files-button').on('click', () => {
        $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'inline-block');
        var data = { 
            action: 'etfupdatefile'
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response);
                $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', true);
                $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            }
        })
        .fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
        });
    });
    
});