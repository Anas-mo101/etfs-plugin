
const populatePreviewTable = (res) => { 
    let ror_state = res.monthly_ror['fetch failed'] ? false : true;
    let dist_state = res.dist_memo['fetch failed'] ? false : true;

    // Fund Details
    let currentDate = new Date().toLocaleDateString();
    document.getElementById('ETF-Pre-sec-yield').value = ror_state ? res.monthly_ror.sec_yeild : 'No Data';
    document.getElementById('ETF-Pre-rate-date-fund-details').value = currentDate;

    // Fund Data & Pricing
    document.getElementById('ETF-Pre-rate-date').value = currentDate;
    document.getElementById('ETF-Pre-rate-date-net').value = res.nav.body[0]['Rate Date'];
    document.getElementById('ETF-Pre-nav-assets').value = res.nav.body[0]['Net Assets'];
    document.getElementById('ETF-Pre-nav-value').value = res.nav.body[0]['NAV'];
    document.getElementById('ETF-Pre-shares-outstanding').value = res.nav.body[0]['Shares Outstanding'];
    document.getElementById('ETF-Pre-premium-discount-percentage').value = res.nav.body[0]['Premium/Discount'] + '%';
    document.getElementById('ETF-Pre-closing-price').value = res.nav.body[0]['Rate Date'];
    document.getElementById('ETF-Pre-median-spread-per').value = res.nav.body[0]['Median 30 Day Spread Percentage'];

    // Graph data 
    
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
        subtitle: { text: `As of ${res.nav.body[0]['Rate Date']}` },
        xAxis: { type: 'datetime', labels: { format: '{value:%b %d, %Y}' } },
        yAxis: { title: { text: 'NAV', enabled: true, } },
        series: [{ name: 'NAV', data: preGraphDataSet, tooltip: { valueDecimals: 2 } }]
    });

    // Outcome Period Values
    document.getElementById('ETF-Pre-outcome-period-update-date').value = currentDate;

    // Current Outcome Period Values
    document.getElementById('ETF-Pre-current-outcome-period-update-date').value = currentDate;

    // Performance
    document.getElementById('ETF-Pre-preformance-update-date').value = currentDate;
    document.getElementById('ETF-Pre-market-price-three').value = ror_state ? res.monthly_ror.market_price.three_months : 'No Data';
    document.getElementById('ETF-Pre-market-price-six').value = ror_state ? res.monthly_ror.market_price.six_months : 'No Data';
    document.getElementById('ETF-Pre-market-price-year').value = ror_state ? res.monthly_ror.market_price.one_year : 'No Data';
    document.getElementById('ETF-Pre-market-price-inception').value = ror_state ? res.monthly_ror.market_price.inception : 'No Data';

    document.getElementById('ETF-Pre-nav-three').value = ror_state ? res.monthly_ror.fund_nav.three_months : 'No Data';
    document.getElementById('ETF-Pre-nav-six').value = ror_state ? res.monthly_ror.fund_nav.six_months : 'No Data';
    document.getElementById('ETF-Pre-nav-year').value = ror_state ? res.monthly_ror.fund_nav.one_year : 'No Data';
    document.getElementById('ETF-Pre-nav-inception').value = ror_state ? res.monthly_ror.fund_nav.inception : 'No Data';

    document.getElementById('ETF-Pre-sp-three').value = ror_state ? res.monthly_ror.sp.three_months : 'No Data';
    document.getElementById('ETF-Pre-sp-six').value = ror_state ? res.monthly_ror.sp.six_months : 'No Data';
    document.getElementById('ETF-Pre-sp-year').value = ror_state ? res.monthly_ror.sp.one_year : 'No Data';
    document.getElementById('ETF-Pre-sp-inception').value = ror_state ? res.monthly_ror.sp.inception : 'No Data';


    // Distribution Detail
    document.getElementById('ETF-Pre-ex-date').value = dist_state ? res.dist_memo.ex_date : 'No Data';
    document.getElementById('ETF-Pre-rec-date').value = dist_state ? res.dist_memo.rec_date : 'No Data';
    document.getElementById('ETF-Pre-pay-date').value = dist_state ? res.dist_memo.pay_date : 'No Data';
    document.getElementById('ETF-Pre-amount-date').value = dist_state ? res.dist_memo.dis_rate_share : 'No Data';

    // TOP 10 HOLDINGS // sort by market value 
    let holderShown = [];
    document.getElementById('ETF-Pre-holdings-containers').innerHTML = '';
    document.getElementById('ETF-Pre-top-holding-update-date').innerHTML = currentDate;
    for(let x = 0; x < 4; x++){
        const holder = res.holdings.body[x];
        const htmlHolder = `<div class="table-horizontal-row-grid"> 
                                <p style="margin: 20px 0;"> ${holder.Weightings} </p>
                                <p style="margin: 20px 0;"> ${holder.SecurityName} </p>
                                <p style="margin: 20px 0;"> ${holder.StockTicker} </p>
                                <p style="margin: 20px 0;"> ${holder.CUSIP} </p>
                                <p style="margin: 20px 0;"> ${holder.Shares} </p>
                                <p style="margin: 20px 0;"> ${holder.MarketValue} </p>
                            </div>`;
        holderShown.push(holder);
        document.getElementById('ETF-Pre-holdings-containers').innerHTML = document.getElementById('ETF-Pre-holdings-containers').innerHTML + htmlHolder;
    }


    document.getElementById("ETF-Pre-popup-underlay").style.display = "block";
    save_previewed_data(res,holderShown,preGraphDataSet);
}

function save_previewed_data(res,holderShown = [],preGraphDataSet = []){
    let ror_state = res.monthly_ror['fetch failed'] ? false : true;
    let dist_state = res.dist_memo['fetch failed'] ? false : true;
    let currentDate = new Date().toLocaleDateString();

    // --> fund detials
    document.getElementById('ETF-Pre-rate-date-fund-details-data').value = currentDate;
    document.getElementById("ETF-Pre-inception-date-data").value = document.getElementById("ETF-Pre-inc-date-previewform").value.trim();
    document.getElementById("ETF-Pre-cusip-data").value = document.getElementById("ETF-Pre-cus-ip-previewform").value.trim();
    document.getElementById("ETF-Pre-fund-listing-data").value = document.getElementById("ETF-Pre-fund-listing-previewform").value.trim();
    document.getElementById("ETF-Pre-iopv-symbol-data").value = document.getElementById("ETF-Pre-iopv-symbol-previewform").value.trim();
    document.getElementById("ETF-Pre-primary-exchange-data").value = document.getElementById("ETF-Pre-primary-exchange-previewform").value.trim();
    document.getElementById("ETF-Pre-nav-sybmol-data").value = document.getElementById("ETF-Pre-nav-symbol-previewform").value.trim();
    document.getElementById("ETF-Pre-ticker-data").value = document.getElementById("ETF-Pre-ticker-previewform").value.trim();
    document.getElementById("ETF-Pre-expense-raito-data").value = document.getElementById("ETF-Pre-expense-ratio-previewform").value.trim();
    document.getElementById("ETF-Pre-sec-yeild-data").value = ror_state ? res.monthly_ror.sec_yeild : '';

    // --> Fund Data & Pricing
    document.getElementById('ETF-Pre-fund-pricing-date-data').value = currentDate;
    document.getElementById('ETF-Pre-net-assets-data').value = res.nav.body[0]['Net Assets'];
    document.getElementById('ETF-Pre-na-v-data').value = res.nav.body[0]['NAV'];
    document.getElementById('ETF-Pre-shares-out-standig-data').value = res.nav.body[0]['Shares Outstanding'];
    document.getElementById('ETF-Pre-discount-percentage-data').value = res.nav.body[0]['Premium/Discount'] + '%';
    document.getElementById('ETF-Pre-closing-price-data').value = res.nav.body[0]['Rate Date'];
    document.getElementById('ETF-Pre-thirty-day-median-data').value = res.nav.body[0]['Median 30 Day Spread Percentage'];

    // --> graph
    document.getElementById("ETF-Pre-graph-json-data").value = JSON.stringify(preGraphDataSet);
    let preGraphDataSet = document.getElementById('ETF-Pre-graph-json-data').value;
    preGraphDataSet = preGraphDataSet == '' || preGraphDataSet == null ? [] : JSON.parse(preGraphDataSet); 
    let parsedNav = parseFloat(res.nav.body[0]['NAV']);
    const newGraphData =  [Date.now(),parsedNav];
    preGraphDataSet.push(newGraphData);

    document.getElementById("ETF-Pre-popup-submit-button").addEventListener('click', () => { 
        console.log("save data");

        //set data in feilds 
        
        if(document.getElementById("ETF-Pre-fund-detials-section").checked){
            // add update date

        }

        
        if(document.getElementById("ETF-Pre-fund-pricing-section").checked){
            
        }
        
        
        if(document.getElementById("ETF-Pre-draw-graph-section").checked){
            
        }

        // --> preformance 
        if(document.getElementById("ETF-Pre-top-holdings-section").checked){
            document.getElementById('ETF-Pre-pref-date-data').value = currentDate;

            document.getElementById('ETF-Pre-perf-market-three-data').value = ror_state ? res.monthly_ror.market_price.three_months  : '';
            document.getElementById('ETF-Pre-perf-market-six-data').value = ror_state ? res.monthly_ror.market_price.six_months  : '';
            document.getElementById('ETF-Pre-perf-market-year-data').value = ror_state ? res.monthly_ror.market_price.one_year  : '';
            document.getElementById('ETF-Pre-perf-market-inception-data').value = ror_state ? res.monthly_ror.market_price.inception  : '';

            document.getElementById('ETF-Pre-perf-nav-three-data').value = ror_state ? res.monthly_ror.fund_nav.three_months : '';
            document.getElementById('ETF-Pre-perf-nav-six-data').value = ror_state ? res.monthly_ror.fund_nav.six_months : '';
            document.getElementById('ETF-Pre-perf-nav-year-data').value = ror_state ? res.monthly_ror.fund_nav.one_year : '';
            document.getElementById('ETF-Pre-perf-nav-inception-data').value = ror_state ? res.monthly_ror.fund_nav.inception : '';

            document.getElementById('ETF-Pre-perf-sp-three-data').value = ror_state ? res.monthly_ror.sp.three_months : '';
            document.getElementById('ETF-Pre-perf-sp-six-data').value = ror_state ? res.monthly_ror.sp.six_months : '';
            document.getElementById('ETF-Pre-perf-sp-year-data').value = ror_state ? res.monthly_ror.sp.one_year : '';
            document.getElementById('ETF-Pre-perf-sp-inception-data').value = ror_state ? res.monthly_ror.sp.inception : '';
        }

        // --> Outcome Period Values
        if(document.getElementById("ETF-Pre-outcome-period-section").checked){
            document.getElementById('ETF-Pre-outcome-period-date-data').value = currentDate;
            document.getElementById("ETF-Pre-etf-starting-return-data").value = document.getElementById("ETF-Pre-etf-starting-return").value.trim();
            document.getElementById("ETF-Pre-spx-index-price-data").value = document.getElementById("ETF-Pre-spx-index-price").value.trim();
            document.getElementById("ETF-Pre-downside-buffer-data").value = document.getElementById("ETF-Pre-downside-buffer").value.trim();
            document.getElementById("ETF-Pre-expected-upside-data").value = document.getElementById("ETF-Pre-expected-upside").value.trim();
            document.getElementById("ETF-Pre-days-remaining-data").value = document.getElementById("ETF-Pre-days-remaining").value.trim();
        }

        // --> Current Outcome Period Values
        if(document.getElementById("ETF-Pre-current-outcome-period-section").checked){
            document.getElementById('ETF-Pre-current-outcome-period-date-data').value = currentDate;
            document.getElementById("ETF-Pre-current-etf-return-data").value = document.getElementById("ETF-Pre-current-etf-return").value.trim();
            document.getElementById("ETF-Pre-current-spx-return-data").value = document.getElementById("ETF-Pre-current-spx-return").value.trim();
            document.getElementById("ETF-Pre-current-remaining-buffer-data").value = document.getElementById("ETF-Pre-current-remaining-buffer").value.trim();
            document.getElementById("ETF-Pre-current-downside-buffer-data").value = document.getElementById("ETF-Pre-current-downside-buffer").value.trim();
            document.getElementById("ETF-Pre-current-remaining-outcome-data").value = document.getElementById("ETF-Pre-current-remaining-outcome").value.trim();
        }

        // --> Distribution Detail
        if(document.getElementById("ETF-Pre-distribution-detail-section").checked){
            document.getElementById('ETF-Pre-ex-date-data').value = dist_state ?  res.dist_memo.ex_date : '';
            document.getElementById('ETF-Pre-rec-date-data').value = dist_state ?  res.dist_memo.rec_date : '';
            document.getElementById('ETF-Pre-pay-date-data').value = dist_state ?  res.dist_memo.pay_date : '';
            document.getElementById('ETF-Pre-dis-rate-share-data').value = dist_state ?  res.dist_memo.dis_rate_share : '';
        }

        // --> holdings
        if(document.getElementById("ETF-Pre-top-holdings-section").checked){
            document.getElementById("ETF-Pre-top-holding-update-date-data").value = currentDate;
            document.getElementById("ETF-Pre-top-holders-data").value = JSON.stringify(holderShown);
        }
        
        //close form
        document.getElementById("ETF-Pre-popup-underlay").style.display = "none";
    });
}

const closeForm = () =>{ 
    document.getElementById("ETF-Pre-popup-underlay").style.display = "none";
}
