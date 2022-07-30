
function isValidHttpUrl(string) {
    let url;
    try {
        url = new URL(string);
    } catch (_) {
        return null;  
    }
    
    if(url.protocol === "http:" || url.protocol === "https:"){
        return string;
    }else{
        return null;
    }
}

jQuery( document ).ready( function( $ ) { 

    $( '#ETF-Pre-add-field-submit-button' ).click( function(e) {
        let f_n = $('#ETF-Pre-new-fund-field-doc').val().trim() !== '' ? $('#ETF-Pre-new-fund-field-doc').val().trim() : false;

        if(f_n === false){
            $('#ETF-Pre-new-fund-field-doc-status').html('<p style="color: red;">Name can not be empty.</p>')
            e.preventDefault();
            return;
        }

        data = {
            action: 'add_new_fund_field',
            field_name: f_n,
        }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
        })
    });

    $( '.ETF-Pre-delete-field-doc' ).click( function(e) {
        _field_id = this.getAttribute('data-fieldid');
        $('#ETF-Pre-popup-underlay-del-fund-field').css('display','flex');
        $( "#ETF-Pre-del-field-submit-button" ).click(function() {
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: 'del_new_fund_field',
                    field_id: _field_id,
                },
                cache: false,
            })
        });
    });

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
        
        var data = {
            action: 'gsd',
            gsURLstate: toggle_state_a,
            gsURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-nav-url${ (toggle_state_a) ? "-google-link" : "-upload-link"}`).value.trim()),
            hlURLstate: toggle_state_b,
            hlURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-holding-url${ (toggle_state_b) ? "-google-link" : "-upload-link"}`).value.trim()),
            monthlyRorURL: isValidHttpUrl(document.getElementById('ETF-Pre-pdf-monthly-ror-url').value.trim()),
            distMemoURL: isValidHttpUrl(document.getElementById('ETF-Pre-pdf-disturbion-url').value.trim()),
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
                // save_file_fetched_data(response);
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $('.ETF-Pre-status-states').css('display', 'none');
            $('#ETF-Pre-status-failed-url').css('display', 'block');
        });
    });

    $( '#etf-manual-edit-button' ).click( function(e) {
        $('#ETF-Pre-popup-underlay').css('display', 'flex');
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