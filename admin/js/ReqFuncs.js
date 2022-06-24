
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
            etfName: document.getElementById('title').value.trim(),
            eftFullName: document.getElementById('ETF-Pre-etf-full-name').value.trim()
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
                save_file_fetched_data(response);
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $('.ETF-Pre-status-states').css('display', 'none');
            $('#ETF-Pre-status-failed-url').css('display', 'block');
        });
    });

    $( '#etf-manual-edit-button' ).click( function(e) {
        document.getElementById("ETF-Pre-popup-underlay").style.display = "block";
        save_manually_edited_data()
    })

    let preGraphDataSet = document.getElementById('ETF-Pre-graph-json-data').value;
    preGraphDataSet = preGraphDataSet == '' || preGraphDataSet == null ? [] : JSON.parse(preGraphDataSet); 
    Highcharts.chart("ETF-Pre-graphcontainer", {
        time: { useUTC: false },
        navigator: { enabled: true },
        rangeSelector: {
            buttons: [
                { count: 1, type: 'month', text: '1m'},
                { count: 3, type: 'month', text: '3m'}, 
                { count: 6, type: 'month', text: '6m'}, 
                { count: 1, type: 'year', text: '1y'}, 
                { count: 3, type: 'year', text: '3y'}, 
                { count: 5, type: 'year', text: '5y'},
                { type: 'all', text: 'All'}
            ],
            inputEnabled: true,
            selected: 0
        },
        title: { text: 'Historical NAV Change' },
        subtitle: { text: `As of today` },
        xAxis: { type: 'datetime', labels: { format: '{value:%b %d, %Y}' } },
        yAxis: { title: { text: 'NAV', enabled: true, } },
        series: [{ name: 'NAV', data: preGraphDataSet, tooltip: { valueDecimals: 2 } }]
    });
});