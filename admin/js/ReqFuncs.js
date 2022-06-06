document.querySelector('.page-title-action').style.display = 'none';
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
        
        if( !isValidHttpUrl(document.getElementById('ETF-Pre-google-nav-url').value.trim()) 
            || !isValidHttpUrl(document.getElementById('ETF-Pre-google-holding-url').value.trim())
                || !isValidHttpUrl(document.getElementById('ETF-Pre-pdf-monthly-ror-url').value.trim()) 
                    || !isValidHttpUrl(document.getElementById('ETF-Pre-pdf-disturbion-url').value.trim()) ){
            $('.ETF-Pre-status-states').css('display', 'none');
            $('#ETF-Pre-status-failed-url').css('display', 'block');
            return;
        }

        var data = {
            action: 'gsd',
            gsURL: document.getElementById('ETF-Pre-google-nav-url').value.trim(),
            hlURL: document.getElementById('ETF-Pre-google-holding-url').value.trim(),
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