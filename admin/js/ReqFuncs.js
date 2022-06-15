
function isValidHttpUrl(string) {
    let url;
    try {
        url = new URL(string);
    } catch (_) {
        return false;  
    }
    return url.protocol === "http:" || url.protocol === "https:";
}

jQuery( document ).ready( function( $ ) { 
    $.ajaxSetup({ cache: false });
    $( '#etf-sheet-sync-button' ).click( function(e) {
        e.preventDefault();
        $('.ETF-Pre-status-states').css('display', 'none');
        $('#ETF-Pre-loadinganimation').css('display', 'block');
        
        let toggle_state_a = false;
        if(document.getElementById('ETF-Pre-google-nav-url-toggle-file-option').dataset.state === "google"){
            toggle_state_a = true;
        }

        let toggle_state_b = false;
        if(document.getElementById('ETF-Pre-google-holding-url-toggle-file-option').dataset.state === "google"){
            toggle_state_b = true;
        }

        if( !isValidHttpUrl(document.getElementById(`ETF-Pre-google-nav-url${ (toggle_state_a) ? "-google-link" : "-upload-link"}`).value.trim()) 
            || !isValidHttpUrl(document.getElementById(`ETF-Pre-google-holding-url${ (toggle_state_b) ? "-google-link" : "-upload-link"}`).value.trim())
                || !isValidHttpUrl(document.getElementById('ETF-Pre-pdf-monthly-ror-url').value.trim()) 
                    || !isValidHttpUrl(document.getElementById('ETF-Pre-pdf-disturbion-url').value.trim()) ){
            $('.ETF-Pre-status-states').css('display', 'none');
            $('#ETF-Pre-status-failed-url').css('display', 'block');
            return;
        }
        
        var data = { 
            action: 'gsd',
            gsURLstate: toggle_state_a,
            gsURL: document.getElementById(`ETF-Pre-google-nav-url${ (toggle_state_a) ? "-google-link" : "-upload-link"}`).value.trim(),
            hlURLstate: toggle_state_b,
            hlURL: document.getElementById(`ETF-Pre-google-holding-url${ (toggle_state_b) ? "-google-link" : "-upload-link"}`).value.trim(),
            monthlyRorURL: document.getElementById('ETF-Pre-pdf-monthly-ror-url').value.trim(),
            distMemoURL: document.getElementById('ETF-Pre-pdf-disturbion-url').value.trim(),
            etfName: document.getElementById('title').value.trim()
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response)
                $('.ETF-Pre-status-states').css('display', 'none');
                $('#ETF-Pre-status-success').css('display', 'block');
                populatePreviewTable(response);
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $('.ETF-Pre-status-states').css('display', 'none');
            $('#ETF-Pre-status-failed-url').css('display', 'block');
        });
    });
});