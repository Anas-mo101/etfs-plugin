jQuery( document ).ready( function( $ ) { 
    $('.save-button').on('click', save_onclick);
    $('.cancel-button').on('click', cancel_onclick);
    $('.edit-button').on('click', edit_onclick);
    cancel_onclick();

    function edit_onclick(){
        $('.save-button, .cancel-button').show();
        $("#ETFs-Pre-auto").prop('disabled', false);
        $('.edit-button').hide();
        $("input").prop('disabled', false);
        $("select").prop("disabled", false);
    }

    function cancel_onclick(){
        $("input, select").prop('disabled', true);
        $("#ETFs-Pre-auto").prop('disabled', true);
        $('select').prop('disabled', 'disabled');
        $('.save-button, .cancel-button').hide();
        $('.edit-button').show();
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
                cancel_onclick();
                $("#ETFs-Pre-loadinganimation").css('display', 'none');
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $("#ETFs-Pre-loadinganimation").css('display', 'none');
        });
    }
});