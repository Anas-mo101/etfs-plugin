
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


    $( '#add-dis-detail-row' ).click( function(e) {
        $( '#dis-loading-show' ).css('display','block');
        data = {
            action: 'add_disturbion_row',
            etfName: document.getElementById('title').value.trim(),
        }

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                console.log(response)
                if(response.success){
                    print_dis_row(response.data);
                }
                $( '#dis-loading-show' ).css('display','none');
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $( '#dis-loading-show' ).css('display','none');
        });
    });

    function print_dis_row(rows_data){
        const etfName = document.getElementById('title').value.trim();
        if(Array.isArray(rows_data)){
            if(rows_data.length > 0){
                let rows = document.createElement('div');
                $('#dis-detail-row-container').html('');
                for (let index = 0; index < rows_data.length; index++) {
                    const element = rows_data[index];
                    const row = 
                    `<div id="${etfName + '-dis-' + index}" style="padding: 10px 0;" class="table-horizontal-row-grid table-horizontal-row-grid-4-icon"> 
                        <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-1" value="${element['ex-date']}" />
                        <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-2" value="${element['rec-date']}" />
                        <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-3" value="${element['pay-date']}" />
                        <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-4" value="${element['amount']}" />
                        <button class="del-dis-detail-row" data-count="${index}" type="button" style="border: none; background: inherit; cursor: pointer;">
                            <svg style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </button>
                    </div>`;
                    rows.innerHTML = rows.innerHTML + row;
                }
                $('#dis-detail-row-container').append(rows);
            }else{
                $('#dis-detail-row-container').html('');
            }
        }
    }

    $('#dis-detail-row-container').on('click', '.del-dis-detail-row', function(e) {
        $( '#dis-loading-show' ).css('display','block');
        _row_id = this.getAttribute('data-count');
        data = { action: 'del_disturbion_row', etfName: document.getElementById('title').value.trim(), index: _row_id }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data,
            cache: false,
            success: function( response ) {
                if(response.success){
                    print_dis_row(response.data);
                }
                $( '#dis-loading-show' ).css('display','none');
            }
        })
        .fail(function(error) {
            console.log("response failed");
            $( '#dis-loading-show' ).css('display','none');
        });
    });

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

        let toggle_state_c = false;
        if(document.getElementById('ETF-Pre-pdf-monthly-ror-url-toggle-file-option').dataset.state === "google"){
            toggle_state_c = true;
        }

        let toggle_state_d = false;
        if(document.getElementById('ETF-Pre-pdf-disturbion-url-toggle-file-option').dataset.state === "google"){
            toggle_state_d = true;
        }

        var data = {
            action: 'gsd',
            gsURLstate: toggle_state_a,
            gsURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-nav-url${ (toggle_state_a) ? "-google-link" : "-upload-link"}`).value.trim()),
            hlURLstate: toggle_state_b,
            hlURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-holding-url${ (toggle_state_b) ? "-google-link" : "-upload-link"}`).value.trim()),
            monthlyRorstate: toggle_state_c,
            monthlyRorURL: isValidHttpUrl(document.getElementById(`ETF-Pre-pdf-monthly-ror-url${ (toggle_state_c) ? "-google-link" : "-upload-link"}`).value.trim()),
            distMemoURLstate: toggle_state_d,
            distMemoURL: isValidHttpUrl(document.getElementById(`ETF-Pre-pdf-disturbion-url${ (toggle_state_d) ? "-google-link" : "-upload-link"}`).value.trim()),
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