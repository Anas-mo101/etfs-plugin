jQuery( document ).ready( function( $ ) { 
    let toggle_and_not_saved = false;
    $('.save-button').on('click', save_onclick);
    $('.cancel-button').on('click', cancel_onclick);
    $('.edit-button').on('click', edit_onclick);
    $('#ETFs-Pre-auto').on('click', toggle_switch_text);
    cancel_onclick(false);

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
        $("input, select").prop('disabled', true);
        $("#ETFs-Pre-auto").prop('disabled', true);
        $('select').prop('disabled', 'disabled');
        $('.save-button, .cancel-button').hide();
        $('.edit-button').show();
        if(flag) toggle_switch_reset();
        toggle_and_not_saved = false;
    }

    function save_onclick(){
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
            console.log("response failed");
            $("#ETFs-Pre-loadinganimation").css('display', 'none');
        });
    }
});