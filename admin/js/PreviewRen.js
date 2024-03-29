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

        if(document.getElementById('in-category-21').checked === false){
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
