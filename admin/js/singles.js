var media_selector_frame;

const baseURL = "https://" + window.location.hostname + "/wp-json/etf-rest/v1";

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('add-dis-detail-row').addEventListener('click', add_dist_detail_row);
    document.getElementById('dis-detail-row-container').addEventListener('click', dist_detail_row_container);
    document.getElementById('ETF-Pre-add-field-submit-button').addEventListener('click', submit_feild_button);
    document.getElementById('etf-sheet-sync-button').addEventListener('click', sync_sheet);
    document.getElementById('etf-manual-edit-button').addEventListener('click', manual_edit);


    const items = document.querySelectorAll('.ETF-Pre-delete-field-doc');
    items.forEach((item)  => item.addEventListener('click', delete_feild))

    let preGraphDataSet = document.getElementById('ETF-Pre-graph-json-data')?.value ?? "[]";
    initGragh(preGraphDataSet);
});

const media_file_selector = (identifier,tog_flag,file_type) => {
    if (media_selector_frame) media_selector_frame = null;

    media_selector_frame = wp.media({
        title: 'Select CSV/XLSM/XLSX file',
        button: {
            text: 'Insert'
        },
        multiple: false,
        library: {
            type: file_type
        },
        uploader: {
            type: file_type
        }
    }).on('select', function () {
        var attachment = media_selector_frame.state().get('selection').first().toJSON();
        if(tog_flag){
            document.getElementById(`ETF-Pre-${identifier}-upload-link`).value = attachment.url;
        }else{
            document.getElementById(`ETF-Pre-${identifier}`).value = attachment.url;
        }
    });

    media_selector_frame.open();
}

const delete_feild = (e) => {
    e.preventDefault();

    const id = e.currentTarget.dataset.fieldid;
    document.getElementById('ETF-Pre-popup-underlay-del-fund-field').style.display = 'flex';
    document.getElementById('ETF-Pre-del-field-submit-button').addEventListener('click', async (ep) => {
        ep.preventDefault();

        fetch(baseURL + "/remove/funddoc", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                field_id: id
            }),
            cache: 'no-cache'
        }).finally(() => {
            location.reload();
        });
    });
}

async function submit_feild_button(e) {
    e.preventDefault();

    const f_n = document.getElementById('ETF-Pre-new-fund-field-doc').value.trim();
    if (f_n === '') {
        document.getElementById('ETF-Pre-new-fund-field-doc-status').innerHTML = '<p style="color: red;">Name can not be empty.</p>';
        e.preventDefault();
        return;
    }

    await fetch(baseURL + "/add/funddoc", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            field_name: f_n,
        }),
        cache: 'no-cache'
    });

    location.reload();
}

const manual_edit = (e) => {
    document.getElementById('ETF-Pre-popup-underlay').style.display = 'flex';
    save_manually_edited_data();
}

const sync_sheet = (e) => {
    e.preventDefault();
    document.querySelectorAll('.ETF-Pre-status-states').forEach(item => item.style.display = 'none');
    document.getElementById('ETF-Pre-loadinganimation').style.display = 'block';

    const toggleStateA = document.getElementById('ETF-Pre-google-nav-url-toggle-file-option').dataset.state === "google";
    const toggleStateB = document.getElementById('ETF-Pre-google-holding-url-toggle-file-option').dataset.state === "google";
    const toggleStateC = document.getElementById('ETF-Pre-pdf-monthly-ror-url-toggle-file-option').dataset.state === "google";
    const toggleStateD = document.getElementById('ETF-Pre-pdf-disturbion-url-toggle-file-option').dataset.state === "google";

    const data = {
        gsURLstate: toggleStateA,
        gsURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-nav-url${toggleStateA ? "-google-link" : "-upload-link"}`).value.trim()),
        hlURLstate: toggleStateB,
        hlURL: isValidHttpUrl(document.getElementById(`ETF-Pre-google-holding-url${toggleStateB ? "-google-link" : "-upload-link"}`).value.trim()),
        monthlyRorstate: toggleStateC,
        monthlyRorURL: isValidHttpUrl(document.getElementById(`ETF-Pre-pdf-monthly-ror-url${toggleStateC ? "-google-link" : "-upload-link"}`).value.trim()),
        distMemoURLstate: toggleStateD,
        distMemoURL: isValidHttpUrl(document.getElementById(`ETF-Pre-pdf-disturbion-url${toggleStateD ? "-google-link" : "-upload-link"}`).value.trim()),
        etfName: document.getElementById('title').value.trim(),
        eftFullName: document.getElementById('ETF-Pre-etf-full-name').value.trim()
    };

    fetch(baseURL + "/fetch/data", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
        cache: 'no-cache'
    })
    .then(response => response.json())
    .then(response => {
        console.log(response);
        document.querySelectorAll('.ETF-Pre-status-states').forEach(item => item.style.display = 'none');
        document.getElementById('ETF-Pre-status-success').style.display = 'block';
        // save_file_fetched_data(response);
    })
    .catch(error => {
        console.log("response failed");
        document.querySelectorAll('.ETF-Pre-status-states').forEach(item => item.style.display = 'none');
        document.getElementById('ETF-Pre-status-failed-url').style.display = 'block';
    });
}

const dist_detail_row_container = (e) => {
    e.preventDefault();
    if (e.target.closest('.del-dis-detail-row')) {
        document.getElementById('dis-loading-show').style.display = 'block';
        const _row_id = e.target.closest('.del-dis-detail-row').getAttribute('data-count');
        const data = JSON.stringify({
            etfName: document.getElementById('title').value.trim(),
            index: _row_id
        });

        fetch(baseURL + "/remove/dist", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: data,
            cache: 'no-cache'
        })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    print_dis_row(response.data);
                }
                document.getElementById('dis-loading-show').style.display = 'none';
            })
            .catch(error => {
                console.log("response failed");
                document.getElementById('dis-loading-show').style.display = 'none';
            });
    }
}

const add_dist_detail_row = (e) => {
    e.preventDefault();
    document.getElementById('dis-loading-show').style.display = 'block';
    fetch(baseURL + "/add/dist", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            etfName: document.getElementById('title').value.trim(),
        }),
        cache: 'no-cache'
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            print_dis_row(response.data);
        }
        document.getElementById('dis-loading-show').style.display = 'none';
    })
    .catch(error => {
        console.log("response failed");
        document.getElementById('dis-loading-show').style.display = 'none';
    });
}

const print_dis_row = (rowsData) => {
    const etfName = document.getElementById('title').value.trim();
    const container = document.getElementById('dis-detail-row-container');

    if (Array.isArray(rowsData)) {
        container.innerHTML = '';
        if (rowsData.length > 0) {
            const rows = document.createElement('div');
            rowsData.forEach((element, index) => {
                const row = `
                <div id="${etfName + '-dis-' + index}" style="padding: 10px 0;" class="table-horizontal-row-grid table-horizontal-row-grid-4-icon">
                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-1" value="${element['ex-date']}" />
                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-2" value="${element['rec-date']}" />
                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-3" value="${element['pay-date']}" />
                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-dis-${index}-4" value="${element['amount']}" />
                    <select id="ETF-Pre-disturbion-detail-varcol-${index}-5">
                        <option value> None </option>
                        <option ${element['varcol'] === "oi" ? "selected" : ""} value="oi"> Ordinary Income </option>
                        <option ${element['varcol'] === "stcg" ? "selected" : ""} value="stcg"> Short-Term Capital Gains </option>
                        <option ${element['varcol'] === "ltcg" ? "selected" : ""} value="ltcg"> Long-Term Capital Gains </option>
                    </select>
                    <button class="del-dis-detail-row" data-count="${index}" type="button" style="border: none; background: inherit; cursor: pointer;">
                        <svg style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                        </svg>
                    </button>
                </div>`;
                rows.innerHTML += row;
            });
            container.appendChild(rows);
        } else {
            container.innerHTML = '';
        }
    }
}

const initGragh = (data) => {
    Highcharts.chart("ETF-Pre-graphcontainer", {
        time: { useUTC: false },
        navigator: { enabled: true },
        rangeSelector: {
            buttons: [
                { count: 1, type: 'month', text: '1m' },
                { count: 3, type: 'month', text: '3m' },
                { count: 6, type: 'month', text: '6m' },
                { count: 1, type: 'year', text: '1y' },
                { count: 3, type: 'year', text: '3y' },
                { count: 5, type: 'year', text: '5y' },
                { type: 'all', text: 'All' }
            ],
            inputEnabled: true,
            selected: 0
        },
        title: { text: 'Historical NAV Change' },
        subtitle: { text: `As of today` },
        xAxis: { type: 'datetime', labels: { format: '{value:%b %d, %Y}' } },
        yAxis: { title: { text: 'NAV', enabled: true } },
        series: [{
            name: 'NAV',
            data: data === '' || data == null ? [] : JSON.parse(data),
            tooltip: { valueDecimals: 2 }
        }]
    });
}

const toggle_between_gs_and_up = (in_name) => { 
    let tog = document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML.trim();
    if(tog === "Upload file"){
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML = "Google Sheet";
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).dataset.state = "upload";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).name = `ETF-Pre-${in_name}`;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).required = true;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).name = `ETF-Pre-${in_name}-`;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).required = false;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).style.display = 'block';
        document.getElementById(`ETF-Pre-${in_name}-file-upload`).style.display = 'block';
        document.getElementById(`ETF-Pre-${in_name}-google-link`).style.display = 'none';
    }else{
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML = "Upload file";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).name = `ETF-Pre-${in_name}-`;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).required = false;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).name = `ETF-Pre-${in_name}`;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).required = true;
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).dataset.state = "google";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).style.display = 'none';
        document.getElementById(`ETF-Pre-${in_name}-file-upload`).style.display = 'none';
        document.getElementById(`ETF-Pre-${in_name}-google-link`).style.display = 'block';
    }

    let toggle_state_a = false;
    if(document.getElementById('ETF-Pre-google-nav-url-toggle-file-option').dataset.state === "google"){
        toggle_state_a = true;
    }

    let toggle_state_b = false;
    if(document.getElementById('ETF-Pre-google-holding-url-toggle-file-option').dataset.state === "google"){
        toggle_state_b = true;
    }

    let toggle_state_d = false;
    if(document.getElementById('ETF-Pre-pdf-disturbion-url-toggle-file-option').dataset.state === "google"){
        toggle_state_d = true;
    }

    let toggle_state_c = false;
    if(document.getElementById('ETF-Pre-pdf-monthly-ror-url-toggle-file-option').dataset.state === "google"){
        toggle_state_c = true;
    }

    document.getElementById(`ETF-Pre-google-nav-url-toggle-data`).value = toggle_state_a;
    document.getElementById(`ETF-Pre-google-holding-url-toggle-data`).value = toggle_state_b;
    document.getElementById(`ETF-Pre-pdf-monthly-ror-url-toggle-data`).value = toggle_state_c;
    document.getElementById(`ETF-Pre-pdf-disturbion-url-toggle-data`).value = toggle_state_d;
}


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

function format_date(date) {
    const [year, month, day] = date.split('-');
    const result = [month, day, year].join('/');
    return result;
}

function format_date_jdy(date) {
    const [year, month, day] = date.split('-');

    const get_month_name = (m) => {
        const months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        const index = m > 12 || m < 1 ? 0 : m-1;
        return months[index];
    };

    const month_num = parseInt(month) == NaN ? 0 : parseInt(month);
    const month_name = get_month_name(month_num);

    const result = [
        month_name,
        `${day},`, 
        year
    ].join(' ');

    return result;
}

function save_manually_edited_data(){
    document.getElementById("ETF-Pre-popup-submit-button").addEventListener('click', () => { 

        //set data in feilds 
        // --> fund detials
        document.getElementById('ETF-Pre-rate-date-fund-details-data').value = format_date(document.getElementById('ETF-Pre-rate-date-fund-details').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
        document.getElementById("ETF-Pre-inception-date-data").value = format_date( document.getElementById("ETF-Pre-inc-date-previewform").value.trim() ); // convert from yyyy-mm-dd to mm-dd-yyyy
        document.getElementById("ETF-Pre-inception-date-display-data").value = format_date_jdy( document.getElementById("ETF-Pre-inc-date-previewform").value.trim() );
        document.getElementById("ETF-Pre-cusip-data").value = document.getElementById("ETF-Pre-cus-ip-previewform").value.trim();
        document.getElementById("ETF-Pre-fund-listing-data").value = document.getElementById("ETF-Pre-fund-listing-previewform").value.trim();
        document.getElementById("ETF-Pre-iopv-symbol-data").value = document.getElementById("ETF-Pre-iopv-symbol-previewform").value.trim();
        document.getElementById("ETF-Pre-primary-exchange-data").value = document.getElementById("ETF-Pre-primary-exchange-previewform").value.trim();
        document.getElementById("ETF-Pre-nav-sybmol-data").value = document.getElementById("ETF-Pre-nav-symbol-previewform").value.trim();
        document.getElementById("ETF-Pre-ticker-data").value = document.getElementById("ETF-Pre-ticker-previewform").value.trim();
        document.getElementById("ETF-Pre-expense-raito-data").value = document.getElementById("ETF-Pre-expense-ratio-previewform").value.trim();
        document.getElementById("ETF-Pre-sec-yeild-data").value = document.getElementById('ETF-Pre-sec-yield').value.trim();

        // --> Fund Data & Pricing
        document.getElementById('ETF-Pre-rate-date-data').value = format_date(document.getElementById('ETF-Pre-rate-date').value.trim());
        document.getElementById('ETF-Pre-fund-pricing-date-data').value = format_date(document.getElementById('ETF-Pre-rate-date-net').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
        
        document.getElementById('ETF-Pre-net-assets-data').value = document.getElementById('ETF-Pre-nav-assets').value.trim();
        document.getElementById('ETF-Pre-na-v-data').value = document.getElementById('ETF-Pre-nav-value').value.trim();
        document.getElementById('ETF-Pre-shares-out-standig-data').value = document.getElementById('ETF-Pre-shares-outstanding').value.trim();
        document.getElementById('ETF-Pre-discount-percentage-data').value = document.getElementById('ETF-Pre-premium-discount-percentage').value.trim();
        document.getElementById('ETF-Pre-closing-price-data').value = document.getElementById('ETF-Pre-closing-price').value.trim();
        document.getElementById('ETF-Pre-thirty-day-median-data').value = document.getElementById('ETF-Pre-median-spread-per').value.trim();
        
        // --> graph
        if(document.getElementById("ETF-Pre-new-hist-nav-graph").value.trim() !== ''){
            let old_hostorical_nav = document.getElementById("ETF-Pre-graph-json-data").value !== '' ? JSON.parse(document.getElementById("ETF-Pre-graph-json-data").value.trim()) : [];
            let new_hostorical_nav =  parseFloat(document.getElementById("ETF-Pre-new-hist-nav-graph").value.trim());
            const newGraphData =  [Date.now(),new_hostorical_nav];
            old_hostorical_nav.push(newGraphData);
            document.getElementById("ETF-Pre-graph-json-data").value = JSON.stringify(old_hostorical_nav);
            document.getElementById('ETF-Pre-graph-json-date-data').value = new Date().toLocaleDateString();
        }
        
        // --> preformance 
        document.getElementById('ETF-Pre-pref-date-data').value = format_date(document.getElementById('ETF-Pre-preformance-update-date').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
        document.getElementById('ETF-Pre-perf-market-three-data').value = document.getElementById('ETF-Pre-market-price-three').value.trim();
        document.getElementById('ETF-Pre-perf-market-six-data').value = document.getElementById('ETF-Pre-market-price-six').value.trim();
        document.getElementById('ETF-Pre-perf-market-year-data').value = document.getElementById('ETF-Pre-market-price-year').value.trim();
        document.getElementById('ETF-Pre-perf-market-five-year-data').value = document.getElementById('ETF-Pre-market-price-five-year').value.trim();
        document.getElementById('ETF-Pre-perf-market-inception-data').value = document.getElementById('ETF-Pre-market-price-inception').value.trim();
        document.getElementById('ETF-Pre-perf-nav-three-data').value = document.getElementById('ETF-Pre-nav-three').value.trim();
        document.getElementById('ETF-Pre-perf-nav-six-data').value = document.getElementById('ETF-Pre-nav-six').value.trim();
        document.getElementById('ETF-Pre-perf-nav-year-data').value = document.getElementById('ETF-Pre-nav-year').value.trim();
        document.getElementById('ETF-Pre-perf-nav-five-year-data').value = document.getElementById('ETF-Pre-nav-five-year').value.trim();
        document.getElementById('ETF-Pre-perf-nav-inception-data').value = document.getElementById('ETF-Pre-nav-inception').value.trim();
        document.getElementById('ETF-Pre-perf-sp-three-data').value = document.getElementById('ETF-Pre-sp-three').value.trim();
        document.getElementById('ETF-Pre-perf-sp-six-data').value = document.getElementById('ETF-Pre-sp-six').value.trim();
        document.getElementById('ETF-Pre-perf-sp-year-data').value = document.getElementById('ETF-Pre-sp-year').value.trim();
        document.getElementById('ETF-Pre-perf-sp-five-year-data').value = document.getElementById('ETF-Pre-sp-five-year').value.trim();
        document.getElementById('ETF-Pre-perf-sp-inception-data').value = document.getElementById('ETF-Pre-sp-inception').value.trim();

        document.getElementById('ETF-Pre-preformance-section-desc-data').value = document.getElementById('ETF-Pre-preformance-section-desc').value.trim();

        const devCategory = document.getElementById('in-category-21-2');
        const prodCategory = document.getElementById('in-category-21');
        
        if((prodCategory ?? devCategory).checked === false){
            // --> vars
            document.getElementById('ETF-Pre-starting-nav-data').value = document.getElementById('ETF-Pre-starting-nav').value.trim();
            document.getElementById("ETF-Pre-etf-starting-return-data").value = document.getElementById('ETF-Pre-starting-nav').value.trim();
            document.getElementById('ETF-Pre-treasury-yeild-data').value = document.getElementById('ETF-Pre-treasury-yeild').value.trim();
            document.getElementById('ETF-Pre-total-buffer-data').value = document.getElementById('ETF-Pre-total-buffer').value.trim();
            document.getElementById('ETF-Pre-sp-start-data').value = document.getElementById('ETF-Pre-sp-start').value.trim();
            document.getElementById('ETF-Pre-sp-ref-data').value = document.getElementById('ETF-Pre-sp-ref').value.trim();
            document.getElementById('ETF-Pre-distribution-ref-data').value = document.getElementById('ETF-Pre-distribution-ref').value.trim();
            document.getElementById('ETF-Pre-period-end-date-data').value = document.getElementById('ETF-Pre-period-end-date').value.trim();

            document.getElementById('ETF-Pre-product-index-data').value = document.getElementById('ETF-Pre-product-index').value.trim();
            document.getElementById('ETF-Pre-product-participation-rate-data').value = document.getElementById('ETF-Pre-product-participation-rate').value.trim();

            // --> Outcome Period Values
            document.getElementById('ETF-Pre-outcome-period-date-data').value = format_date(document.getElementById('ETF-Pre-outcome-period-update-date').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
            // document.getElementById("ETF-Pre-etf-starting-return-data").value = document.getElementById("ETF-Pre-etf-starting-return").value.trim();
            document.getElementById("ETF-Pre-spx-index-price-data").value = document.getElementById("ETF-Pre-spx-index-price").value.trim();
            document.getElementById("ETF-Pre-downside-buffer-data").value = document.getElementById("ETF-Pre-downside-buffer").value.trim();
            document.getElementById("ETF-Pre-expected-upside-data").value = document.getElementById("ETF-Pre-product-participation-rate").value.trim();
            document.getElementById("ETF-Pre-days-remaining-data").value = document.getElementById("ETF-Pre-days-remaining").value.trim();


            // --> Current Outcome Period Values
            // document.getElementById('ETF-Pre-current-outcome-period-date-data').value = format_date(document.getElementById('ETF-Pre-current-outcome-period-update-date').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
            // document.getElementById("ETF-Pre-current-etf-return-data").value = document.getElementById("ETF-Pre-current-etf-return").value.trim();
            // document.getElementById("ETF-Pre-current-spx-return-data").value = document.getElementById("ETF-Pre-current-spx-return").value.trim();
            // document.getElementById("ETF-Pre-current-remaining-buffer-data").value = document.getElementById("ETF-Pre-current-remaining-buffer").value.trim();
            // document.getElementById("ETF-Pre-current-downside-buffer-data").value = document.getElementById("ETF-Pre-current-downside-buffer").value.trim();
            // document.getElementById("ETF-Pre-current-remaining-outcome-data").value = document.getElementById("ETF-Pre-current-remaining-outcome").value.trim();
            
            document.getElementById("ETF-Pre-preformance-benchmark-label-data").value = document.getElementById("ETF-Pre-preformance-benchmark-selection").innerText.trim();
        }else{
            document.getElementById("ETF-Pre-preformance-benchmark-selection-data").value = document.getElementById("ETF-Pre-preformance-benchmark-selection").value;

            const benchmark = document.getElementById("ETF-Pre-preformance-benchmark-selection").value;
            const benchmark_label = benchmark.split(" - ")[0];
            document.getElementById("ETF-Pre-preformance-benchmark-label-data").value = benchmark_label;
        }


        // --> Distribution Detail
        // document.getElementById('ETF-Pre-ex-date-data').value = document.getElementById('ETF-Pre-ex-date').value.trim();
        // document.getElementById('ETF-Pre-rec-date-data').value = document.getElementById('ETF-Pre-rec-date').value.trim();
        // document.getElementById('ETF-Pre-pay-date-data').value = document.getElementById('ETF-Pre-pay-date').value.trim();
        // document.getElementById('ETF-Pre-dis-rate-share-data').value = document.getElementById('ETF-Pre-amount-date').value.trim();

        let new_dis_data = [];
        const rows_count = document.querySelectorAll('.table-horizontal-row-grid-4-icon').length - 1;
        if(rows_count > 0){
            for (let index = 0; index < rows_count; index++) {
                const new_dis = {
                    "ex-date" : document.getElementById(`ETF-Pre-dis-${index}-1`).value.trim(),
                    "rec-date" : document.getElementById(`ETF-Pre-dis-${index}-2`).value.trim(),
                    "pay-date" : document.getElementById(`ETF-Pre-dis-${index}-3`).value.trim(),
                    "amount" : document.getElementById(`ETF-Pre-dis-${index}-4`).value.trim(),
                    "varcol" : document.getElementById(`ETF-Pre-disturbion-detail-varcol-${index}-5`).value.trim(),
                }
                new_dis_data.push(new_dis);
            }
        }
        document.getElementById("ETF-Pre-disturbion-detail-data").value = JSON.stringify(new_dis_data);
        // document.getElementById("ETF-Pre-disturbion-detail-varcol-data").value = document.getElementById("ETF-Pre-disturbion-detail-varcol").value;


        // --> holdings
        document.getElementById("ETF-Pre-top-holding-update-date-data").value = format_date(document.getElementById('ETF-Pre-top-holding-update-date').value.trim()); // convert from yyyy-mm-dd to mm-dd-yyyy
        let new_holdings_data = [];
        holding_count = document.querySelectorAll('#ETF-Pre-holdings-containers .table-horizontal-row-grid').length;
        holding_count = holding_count <= 0 ? 10 : holding_count;
        for (let index = 0; index < holding_count; index++) {
            const new_holding = {
                "Weightings" : document.getElementById(`ETF-Pre-holding-${index}-1`).value.trim(),
                "SecurityName" : document.getElementById(`ETF-Pre-holding-${index}-2`).value.trim(),
                "StockTicker" : document.getElementById(`ETF-Pre-holding-${index}-3`).value.trim(),
                "CUSIP" : document.getElementById(`ETF-Pre-holding-${index}-4`).value.trim(),
                "Shares" : document.getElementById(`ETF-Pre-holding-${index}-5`).value.trim(),
                "MarketValue" : document.getElementById(`ETF-Pre-holding-${index}-6`).value.trim(),
            }
            new_holdings_data.push(new_holding);
        }
        document.getElementById("ETF-Pre-top-holders-data").value = JSON.stringify(new_holdings_data);
        
        //close form
        document.getElementById("ETF-Pre-popup-underlay").style.display = "none";
    });
}

function save_file_fetched_data(res){
    let ror_state = res.monthly_ror['fetch failed'] ? false : true;
    let dist_state = res.dist_memo['fetch failed'] ? false : true;
    let currentDate = new Date().toLocaleDateString();

    let current_etf_nav_index = null;
    for (let etf_index = 0; etf_index < res.nav.body.length; etf_index++) {
        if(res.nav.body[etf_index]['Fund Ticker'] === document.getElementById('title').value.trim()){
            current_etf_nav_index = etf_index;
            break;
        }
    }   
    
    if(ror_state === true){
        // --> fund detials
        document.getElementById('ETF-Pre-rate-date-fund-details-data').value = currentDate;
        document.getElementById("ETF-Pre-sec-yeild-data").value = res.monthly_ror.sec_yeild;

        // preformance
        document.getElementById('ETF-Pre-pref-date-data').value = currentDate;
        document.getElementById('ETF-Pre-perf-market-three-data').value = res.monthly_ror.market_price.three_months ;
        document.getElementById('ETF-Pre-perf-market-six-data').value = res.monthly_ror.market_price.six_months;
        document.getElementById('ETF-Pre-perf-market-year-data').value = res.monthly_ror.market_price.one_year;
        document.getElementById('ETF-Pre-perf-market-inception-data').value = res.monthly_ror.market_price.inception;
        document.getElementById('ETF-Pre-perf-nav-three-data').value = res.monthly_ror.fund_nav.three_months;
        document.getElementById('ETF-Pre-perf-nav-six-data').value = res.monthly_ror.fund_nav.six_months;
        document.getElementById('ETF-Pre-perf-nav-year-data').value = res.monthly_ror.fund_nav.one_year;
        document.getElementById('ETF-Pre-perf-nav-inception-data').value = res.monthly_ror.fund_nav.inception;
        document.getElementById('ETF-Pre-perf-sp-three-data').value = res.monthly_ror.sp.three_months;
        document.getElementById('ETF-Pre-perf-sp-six-data').value = res.monthly_ror.sp.six_months;
        document.getElementById('ETF-Pre-perf-sp-year-data').value = res.monthly_ror.sp.one_year;
        document.getElementById('ETF-Pre-perf-sp-inception-data').value = res.monthly_ror.sp.inception;
    }
    
    if(current_etf_nav_index !== null){
        // --> Fund Data & Pricing
        document.getElementById('ETF-Pre-rate-date-data').value = currentDate;
        document.getElementById('ETF-Pre-fund-pricing-date-data').value = currentDate;
        document.getElementById('ETF-Pre-net-assets-data').value = res.nav.body[current_etf_nav_index]['Net Assets'];
        document.getElementById('ETF-Pre-na-v-data').value = res.nav.body[current_etf_nav_index]['NAV'];
        document.getElementById('ETF-Pre-shares-out-standig-data').value = res.nav.body[current_etf_nav_index]['Shares Outstanding'];
        document.getElementById('ETF-Pre-discount-percentage-data').value = res.nav.body[current_etf_nav_index]['Premium/Discount Percentage'] + '%';
        document.getElementById('ETF-Pre-closing-price-data').value = res.nav.body[current_etf_nav_index]['Rate Date'];
        document.getElementById('ETF-Pre-thirty-day-median-data').value = res.nav.body[current_etf_nav_index]['Median 30 Day Spread Percentage'];
    
        // --> graph
        document.getElementById('ETF-Pre-graph-json-date-data').value = currentDate;
        let preGraphDataSet = document.getElementById('ETF-Pre-graph-json-data').value;
        preGraphDataSet = preGraphDataSet == '' || preGraphDataSet == null ? [] : JSON.parse(preGraphDataSet); 
        let parsedNav = parseFloat(res.nav.body[current_etf_nav_index]['NAV']);
        const newGraphData =  [Date.now(),parsedNav];
        preGraphDataSet.push(newGraphData);
        document.getElementById('ETF-Pre-graph-json-data').value = JSON.stringify(preGraphDataSet);
    }

    if(dist_state){
        // --> Distribution Detail
        document.getElementById('ETF-Pre-ex-date-data').value = res.dist_memo.ex_date;
        document.getElementById('ETF-Pre-rec-date-data').value = res.dist_memo.rec_date;
        document.getElementById('ETF-Pre-pay-date-data').value = res.dist_memo.pay_date;
        document.getElementById('ETF-Pre-dis-rate-share-data').value = res.dist_memo.dis_rate_share;
    }

    let holderShown = [];
    if(Array.isArray(res.holdings.body) && res.holdings.body.length >= 1){
        res.holdings.body.sort((a, b) => parseFloat(b.MarketValue) - parseFloat(a.MarketValue));
        for(let x = 0; x < 10; x++){
            const holder = res.holdings.body[x]; // sort by top 10 holdings by market value
            holderShown.push(holder);
        } 
        document.getElementById("ETF-Pre-top-holding-update-date-data").value = currentDate;
        document.getElementById("ETF-Pre-top-holders-data").value = JSON.stringify(holderShown);
    }
}

const closeForm = () =>{ 
    Array.from(document.getElementsByClassName('ETF-Pre-general-popup-underlay')).forEach(function(element) {
        element.style.display = 'none';
    });
}