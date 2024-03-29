jQuery( document ).ready( function( $ ) { 
    let toggle_and_not_saved = false;
    $('.cancel-file-button').on('click', cancel_file);
    $('#ETFs-Pre-auto').on('click', toggle_switch_text);
    $('.cancel-button').on('click', cancel_onclick);
    $('.edit-button').on('click', edit_onclick);
    cancel_fp_edits();
    cancel_divs_edits();
    cancel_onclick(false);
    cancel_file();

    $('.edit-file-button').on('click', () => { edit_file_button(); });

    $('.diz-edit-button').on('click', () => { 
        $('.diz-toggle-button').prop('disabled', false);
        $('.diz-layout-fund-edit').prop('disabled', false);
        $('.diz-save-button, .diz-cancel-button').show();
        $('.div-chart-input').prop('disabled', false);
        $('.diz-edit-button').hide();  
    });

    $('.diz-cancel-button').on('click', () => { 
        cancel_divs_edits();
    });

    $('.fp-edit-button').on('click', () => { 
        $('#drop-items').sortable({
            animation: 350,
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag"
        })
        $('.fp-toggle-button').prop('disabled', false);
        $('.fp-layout-fund-edit').prop('disabled', false);
        $('.fp-save-button, .fp-cancel-button').show();
        $('.fp-edit-button').hide();  
    });

    $('.fp-cancel-button').on('click', () => { 
        cancel_fp_edits(true);
    });

    function cancel_fp_edits(arg = false){
        if(arg === true){
            $('#drop-items').sortable('destroy');
        }
        $("#ETFs-Pre-loadinganimation-2").css('display', 'none');
        $('.fp-toggle-button').prop('disabled', true);
        $(`.dropdown-fund-details`).css('display', 'none');
        $('.fp-layout-fund-edit').prop('disabled', true);
        $('.fp-save-button, .fp-cancel-button').hide();
        $('.fp-edit-button').show();  
    }

    function cancel_divs_edits(){
        $("#ETFs-Pre-loadinganimation-3").css('display', 'none');
        $('.diz-toggle-button').prop('disabled', true);
        $('.div-chart-input').prop('disabled', true);
        $('.diz-save-button, .diz-cancel-button').hide();
        $('.diz-edit-button').show();  
    }

    function edit_file_button(){
        $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', false);
        $(".edit-file-button").hide();
        $(".scan-dir-button").show();
        $(".update-files-button").show();
        $(".cancel-file-button").show();
        $(".clear-set-file").show();
    }

    $('#ETFs-Pre-toggle-file-view').change(function(){
        if($('#ETFs-Pre-toggle-file-view').is(':checked')){
            $("#ETFs-Pre-scaned-file-dir").show();
            $("#ETFs-Pre-scaned-file-list-dir").hide();
        }else{
            $("#ETFs-Pre-scaned-file-dir").hide();
            $("#ETFs-Pre-scaned-file-list-dir").show();
        }
    });

    function cancel_file(){
        $("#ETFs-Pre-nav-name, #ETFs-Pre-holding-name, #ETFs-Pre-dist-name, #ETFs-Pre-ror-name").prop('disabled', true);
        $(".edit-file-button").show();
        $(".scan-dir-button").hide();
        $(".update-files-button").hide();
        $(".cancel-file-button").hide();
        $(".clear-set-file").hide();
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

    $('.diz-save-button').on('click', () => {
        $("#ETF-Pre-creds-state-3").hide();
        $("#ETFs-Pre-loadinganimation-3").css('display', 'inline-block');
        $('.diz-toggle-button').prop('disabled', false);

        const data = {
            action: 'dizcharts',
            divz_no_stocks: $.trim( $('#etfs-divz-no-stocks').val() ) == '' ? 0 : $.trim( $('#etfs-divz-no-stocks').val() ),
            sp_no_stocks: $.trim( $('#etfs-sp-no-stocks').val() ) == '' ? 0 : $.trim( $('#etfs-sp-no-stocks').val() ),
            divz_ps: $.trim( $('#etfs-divz-ps').val() )  == '' ? 0 : $.trim( $('#etfs-divz-ps').val() ),
            sp_ps: $.trim( $('#etfs-sp-ps').val() )  == '' ? 0 : $.trim( $('#etfs-sp-ps').val() ),
            divz_pe: $.trim( $('#etfs-divz-pe').val() )  == '' ? 0 : $.trim( $('#etfs-divz-pe').val() ),
            sp_pe: $.trim( $('#etfs-sp-pe').val() )  == '' ? 0 : $.trim( $('#etfs-sp-pe').val() ),
            divz_pb: $.trim( $('#etfs-divz-pb').val() )  == '' ? 0 : $.trim( $('#etfs-divz-pb').val() ),
            sp_pb: $.trim( $('#etfs-sp-pb').val() )  == '' ? 0 : $.trim( $('#etfs-sp-pb').val() ),
            divz_avg: $.trim( $('#etfs-divz-avg').val() )  == '' ? 0 : $.trim( $('#etfs-divz-avg').val() ),
            sp_avg: $.trim( $('#etfs-sp-avg').val() )  == '' ? 0 : $.trim( $('#etfs-sp-avg').val() ),
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response);
                cancel_divs_edits();
            }
        }).fail(function(error) {
            console.log(`response failed: ${error}`);
            cancel_divs_edits();
            $("#ETFs-Pre-loadinganimation-3").css('display', 'none');
            $("#ETF-Pre-creds-state-3").show();
            $("#ETF-Pre-creds-state-3").html("server failed");
        });
    });

    $('.fp-save-button').on('click', () => {
        $("#ETF-Pre-creds-state-2").hide();
        $("#ETFs-Pre-loadinganimation-2").css('display', 'inline-block');
        $('.fp-toggle-button').prop('disabled', false);

        let etfs_arr = [];
        counter = 1;
        const card_order = $(".drop_info");
        for (var obj of card_order) { 
            const card = {
                'id': obj.id,
                'order': counter,
                'display': $(`#vis-${obj.id}`).is(":checked"),
                'type': $.trim( $(`#${obj.id}-fund-type`).val() ),
                'details': $.trim( $(`#${obj.id}-fund-desc`).val() ),
            };
            counter++;
            etfs_arr.push(card);
        };

        const data = {
            action: 'fplayout',
            etfs: etfs_arr,
            structured_title: $.trim( $('#ETFs-Pre-structured-title').val() ),
            structured_subtitle: $.trim( $('#ETFs-Pre-structured-subtitle').val() ),
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response);
                cancel_fp_edits(true);
            }
        }).fail(function(error) {
            console.log(`response failed: ${error}`);
            cancel_fp_edits(true);
            $("#ETFs-Pre-loadinganimation-2").css('display', 'none');
            $("#ETF-Pre-creds-state-2").show();
            $(`.dropdown-fund-details`).css('display', 'none');
            $("#ETF-Pre-creds-state-2").html("server failed");
        });
    });



    $('.save-button').on('click', () => {
        $("#ETF-Pre-creds-state").hide();
        $("#ETFs-Pre-loadinganimation").css('display', 'inline-block');

        host = $("#ETFs-Pre-host").val() === '' ? '*' : $("#ETFs-Pre-host").val();
        user = $("#ETFs-Pre-user").val() === '' ? '*' : $("#ETFs-Pre-user").val();
        pass = $("#ETFs-Pre-pass").val() === '' ? '*' : $("#ETFs-Pre-pass").val();
        port = $("#ETFs-Pre-port").val() === '' ? '*' : $("#ETFs-Pre-port").val();
        freq = $("#ETFs-Pre-freq").val() === '' ? '*' : $("#ETFs-Pre-freq").val();

        var data = { 
            action: 'etfconfig',
            host: host,
            state: $("#ETFs-Pre-auto").is(":checked"),
            user: user,
            pass: pass,
            port: port,
            freq: freq,
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response);
                cancel_onclick(false);
                $("#ETF-Pre-creds-state").html(response.cycle);
                $("#ETF-Pre-creds-state").show();
                if(response.cycle === 'No Required Files Available, Do Allocate Correct File Naming Below'){
                    edit_file_button();
                    $('#ETFs-Pre-auto').prop('checked', false); 
                }else if(response.cycle === 'The ssh2 PHP extension is not available' || 
                             response.cycle === 'connection failed' || 
                                 response.cycle === 'authentication failed'){
                    $('#ETFs-Pre-auto').prop('checked', false);                 
                }   
                $("#ETFs-Pre-loadinganimation").css('display', 'none');
            }
        }).fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation").css('display', 'none');
            $("#ETF-Pre-creds-state").show();
            $("#ETF-Pre-creds-state").html("server failed");
        });
    });

    $('.scan-dir-button').on('click', () => {
        $("#ETF-Pre-file-state").hide();
        $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'inline-block');
        $.ajax({
            type: "GET",
            url: ajaxurl,   
            data: {  action: 'scansftpdir' },
            cache: false,
            success: function( response ) {
                console.log(response);
                document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML = '';
                document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML = '';
                if(Array.isArray(response.files)){
                    $("#ETF-Pre-file-state").show();
                    $("#ETF-Pre-file-state").html("sftp scan successfull");
                    response.files.forEach(file => {
                        let ext = file.split('.').pop();
                        const y = `<li id="${file}" draggable="true" ondragstart="event.dataTransfer.setData('text', '${file}')" > ${file} </li>`;
                        const x = `<div class="tile form" draggable="true" ondragstart="event.dataTransfer.setData('text', '${file}')"> 
                            <div class="file-ext-text"> 
                                <svg style="margin: 20px 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16"> <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/> </svg>
                                <p id="${file}" style="word-break: break-all;">${file}</p> 
                                </div> <p>${ext} file</p> 
                            </div>`;
                        document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML = document.getElementById('ETFs-Pre-scaned-file-dir').innerHTML + x;
                        document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML = document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML + y;
                    });
                }
                
                $("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").prop('disabled', true);
                $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            }
        }).fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            $("#ETF-Pre-file-state").show();
            $("#ETF-Pre-file-state").html("server fail");
        });
    });

    $('.fp-layout-fund-edit').on('click', (e) => {
        if($(`#${e.currentTarget.dataset.fundId}-fund-details`).css( "display" ) === 'none'){
            $(`#${e.currentTarget.dataset.fundId}-fund-details`).css('display', 'block');
        } else{
            $(`#${e.currentTarget.dataset.fundId}-fund-details`).css('display', 'none');
        }
    });

    $('.update-files-button').on('click', () => {
        $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'inline-block');

        let nav = document.getElementById('ETFs-Pre-nav-name').innerText === '' ? '*' : document.getElementById('ETFs-Pre-nav-name').innerText;
        let holding = document.getElementById('ETFs-Pre-holding-name').innerText === '' ? '*' : document.getElementById('ETFs-Pre-holding-name').innerText;
        let ror = document.getElementById('ETFs-Pre-ror-name').innerText === '' ? '*' : document.getElementById('ETFs-Pre-ror-name').innerText;
        let sec = document.getElementById('ETFs-Pre-sec-name').innerText === '' ? '*' : document.getElementById('ETFs-Pre-sec-name').innerText;
        let ind = document.getElementById('ETFs-Pre-ind-name').innerText === '' ? '*' : document.getElementById('ETFs-Pre-ind-name').innerText;

        var data = { 
            action: 'etfupdatefile',
            nav: nav,
            holding: holding,
            ror: ror,
            sec: sec,
            ind: ind
        };

        $.ajax({
            type: "POST",   
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                cancel_file();
                $("#ETF-Pre-file-state").html("File Requirement Update Successfull");
                $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            }
        })
        .fail(function(error) {
            console.log(`response failed: ${error}`);
            $("#ETFs-Pre-loadinganimation-file-settings").css('display', 'none');
            $("#ETF-Pre-file-state").html("File Requirement Update Unsuccessfull");
        });
    });
    
});