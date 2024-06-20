<?php ?> 
<div class="<?php echo $this->prefix ?>general-popup-underlay" id="<?php echo $this->prefix ?>popup-underlay">
    <div id="<?php echo $this->prefix ?>popup-container">
        <div id="<?php echo $this->prefix ?>table-popup">
            <div id="<?php echo $this->prefix ?>popup-topbar-container">  
                <div id="<?php echo $this->prefix ?>popup-title-container"> <h2 id="<?php echo $this->prefix ?>popup-title"> <?php the_title(); ?> Tables Preview </h2> </div>
                <button type="button" id="<?php echo $this->prefix ?>popup-close-button" onclick="closeForm()"> 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
            </div>
            <div id="<?php echo $this->prefix ?>popup-table-container">
             <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="<?php echo $this->prefix ?>popup-table-inner-container">
 
                <div class="template-tables-preview" id="<?php echo $this->prefix ?>fund-data-pricing">
                    <h1 style="font-weight: 600;"> Fund Details </h1>
                    <p>  Data as of 
                        <span id="<?php echo $this->prefix ?>rate-date-fund-details-span">
                            <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>rate-date-fund-details" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "rate-date-fund-details-data", true ))); ?>" />
                        </span>
                    </p>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3>Inception Date </h3> 
                        <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>inc-date-previewform" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "inception-date-data", true ))); ?>" />
                        <h3>CUSIP</h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>cus-ip-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "cusip-data", true ) ) ?>" />
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3>Fund Listing </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>fund-listing-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "fund-listing-data", true ) ) ?>" />
                        <h3> IOPV Symbol  </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>iopv-symbol-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "iopv-symbol-data", true ) ) ?>" />
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3> Primary Exchange </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>primary-exchange-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "primary-exchange-data", true ) ) ?>" />
                        <h3>NAV Symbol </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-symbol-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "nav-sybmol-data", true ) ) ?>" />
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3> Ticker </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>ticker-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "ticker-data", true ) ) ?>" />
                        <h3> Expense Ratio </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>expense-ratio-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "expense-raito-data", true ) ) ?>" />
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <div></div>
                        <div></div>
                        <h3>30 Day SEC Yield* </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sec-yield" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "sec-yeild-data", true )) ?> " />
                    </div>
                </div>

                <div class="template-tables-preview" style="width: 50%;" id="<?php echo $this->prefix ?>fund-data-pricing">

                    <h1 style="font-weight: 600;"> Fund Data & Pricing </h1>
                    <p>  Data as of 
                        <span id="<?php echo $this->prefix ?>rate-date-span">
                            <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>rate-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "rate-date-data", true )));?>" />
                        </span>
                    </p>
                    <div class="table-horizontal-row"> 
                        <h3>Net Assets as of 
                            <span id="<?php echo $this->prefix ?>"> 
                                <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>rate-date-net" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "fund-pricing-date-data", true ))); ?>" />
                            </span>
                        </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-assets" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "net-assets-data", true )) ?> " />
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> NAV </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-value" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "na-v-data", true )) ?> " />
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Shares Outstanding </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>shares-outstanding" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "shares-out-standig-data", true )) ?> " />
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Premium/Discount Percentage </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>premium-discount-percentage" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "discount-percentage-data", true )) ?> " />
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Closing Price </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>closing-price" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "closing-price-data", true )) ?> " />
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3>  Median 30 Day Spread Percentage </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>median-spread-per" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "thirty-day-median-data", true )) ?> " />
                    </div>
                </div>

                <div class="template-tables-preview-" style="width: 800px;">
                    <h1 style="font-weight: 600;"> Historical NAV Graph </h1>
                    <div style='margin-bottom: 10px;'>
                        <p>  Add new NAV Hostorical data (with today's date)
                            <span>  
                                <input type="number" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>new-hist-nav-graph" value="" />
                            </span>
                        </p>
                    </div>
                    <div id="<?php echo $this->prefix ?>graphcontainer">  </div>
                </div>

                <?php if(! in_category( 'Unstructured ETFs' )){ ?>

                    <div class="template-tables-preview" style="width: 50%;" id="<?php echo $this->prefix ?>fund-data-pricing">
                        <h1 style="font-weight: 600;"> ETFs period variables </h1>
                        <p> Used to calculate ETFs data. This section is not displayed </p>
                        <div class="table-horizontal-row"> 
                            <h3> ETF Starting NAV  </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>starting-nav" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "starting-nav-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> Treasury Yield </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>treasury-yeild" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "treasury-yeild-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> Total Buffer </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>total-buffer" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "total-buffer-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> S&P Year Start </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>sp-start" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "sp-start-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> S&P Reference </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>sp-ref" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "sp-ref-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> Period End Date </h3>
                            <input novalidate type="date" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>period-end-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "period-end-date-data", true ))); ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> Distribution </h3>
                            <input type="number" step=".01" class="fund-details-input-feilds" style="width: 120px;" id="<?php echo $this->prefix ?>distribution-ref" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "distribution-ref-data", true )) ?>" />
                        </div>
                    </div>

                    <div class="template-tables-preview" style="width: 50%;" id="<?php echo $this->prefix ?>fund-data-pricing">
                        <h1 style="font-weight: 600;"> Product Table Manual Entries </h1>
                        <p> Displayed in product page. </p>
                        <div class="table-horizontal-row"> 
                            <h3> Index  </h3>
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>product-index" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "product-index-data", true )) ?>" />
                        </div>
                        <div class="table-horizontal-row"> 
                            <h3> Est. Upside Market Participation Rate </h3>
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>product-participation-rate" value="<?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "product-participation-rate-data", true )) ?>" />
                        </div>
                    </div>
                    
                    <div class="template-tables-preview" id="<?php echo $this->prefix ?>outcome-period">
                       
                        <h1 style="font-weight: 600;"> Outcome Period Values </h1>
                        <p>  Data as of 
                            <span id="<?php echo $this->prefix ?>">  
                                <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>outcome-period-update-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "outcome-period-date-data", true ))); ?>" />
                            </span>
                        </p>
                        <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                            <h3>ETF Starting NAV/Period Return</h3> 
                            <h3>SPX Index Reference Price</h3>
                            <h3>Downside Buffer</h3>
                            <h3>Expected Upside Participation</h3> 
                            <h3>Days Remaining</h3>
                        </div>
                        <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>etf-starting-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "etf-starting-return-data", true ) ) . '/0.0%' ?>" /> 
                            <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>spx-index-price" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "spx-index-price-data", true ) ) ?>" />
                            <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>downside-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "downside-buffer-data", true ) ) ?>" />
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>expected-upside" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "expected-upside-data", true ) ) ?>" />
                            <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>days-remaining" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "days-remaining-data", true ) ) ?>" /> 
                        </div>
                    </div>

                    <div class="template-tables-preview" id="<?php echo $this->prefix ?>current-outcome-period">
                        <h1 style="font-weight: 600;"> Current Outcome Period Values </h1>
                        <p>  Data as of 
                            <span id="<?php echo $this->prefix ?>">  
                                <input disabled type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-outcome-period-update-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "current-outcome-period-date-data", true ))); ?>" />
                            </span>
                        </p>
                        <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                            <h3>ETF Current NAV/Period Return</h3> 
                            <h3>SPX Period Return</h3>
                            <h3>Remaining Buffer</h3>
                            <h3>Downside Before Buffer</h3> 
                            <h3>Remaining Outcome Period</h3>
                        </div>
                        <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-etf-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-etf-return-data", true ) ) . '/' . htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-period-return-data", true ) ) ?>" /> 
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-spx-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-spx-return-data", true ) ) ?>" />
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-remaining-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-remaining-buffer-data", true ) ) ?>" />
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-downside-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-downside-buffer-data", true ) ) ?>" />
                            <input readonly style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-remaining-outcome" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-remaining-outcome-data", true ) ) ?>" />
                        </div>
                    </div>

                <?php } ?>

                <div class="template-tables-preview" >
                  
                    <h1 style="font-weight: 600;"> Performance </h1>
                    <p>  Data as of 
                        <span id="<?php echo $this->prefix ?>">  
                            <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>preformance-update-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "pref-date-data", true )));  ?>" />
                        </span>
                    </p>
                    <div class="table-horizontal-row-grid"> 
                        <h3></h3>
                        <h3>3 Month</h3>
                        <h3>6 Month</h3>
                        <h3>1 Year</h3>
                        <h3>5 Year</h3>
                        <h3>Since Inception</h3>
                    </div> 
                    <div id="<?php echo $this->prefix ?>performance-section-containers"> 
                        <div class="table-horizontal-row-grid"> 
                            <p style="margin: 20px 0;"> Market Price </p>
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>market-price-three" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-market-three-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>market-price-six" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-market-six-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>market-price-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-market-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>market-price-five-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-market-five-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>market-price-inception" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-market-inception-data", true )) ?> " />
                        </div>
                        <div class="table-horizontal-row-grid"> 
                            <p style="margin: 20px 0;"> Fund NAV </p>
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-three" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-nav-three-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-six" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-nav-six-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-nav-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-five-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-nav-five-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>nav-inception" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-nav-inception-data", true )) ?> " />
                        </div>
                        <div class="table-horizontal-row-grid"> 
                            <?php if(in_category( 'Unstructured ETFs' )){ 
                                    $variable = get_option('etfs-pre-available-benchmarks');
                                    $variable = json_decode($variable, true);
                                ?>
                                <select style="width: 100%;" id="<?php echo $this->prefix ?>preformance-benchmark-selection">
                                    <?php foreach ($variable as $value) { ?>
                                        <option value="<?php echo $value ?>" <?php if(get_post_meta( get_the_ID(), $this->prefix . "preformance-benchmark-selection-data", true ) == $value){ echo 'selected'; } ?> > <?php echo $value ?> </option>
                                    <?php } ?>
                                </select>
                            <?php }else{ ?>
                                <p id="<?php echo $this->prefix ?>preformance-benchmark-selection" style="margin: 20px 0;"> S&P 500 </p>
                            <?php }?>
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sp-three" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-sp-three-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sp-six" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-sp-six-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sp-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-sp-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sp-five-year" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-sp-five-year-data", true )) ?> " />
                            <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>sp-inception" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "perf-sp-inception-data", true )) ?> " />
                        </div>
                    </div>
                </div>

                <div style="display: contents;" class="template-tables-preview">
                  <h1 style="font-weight: 600; margin-bottom: 20px;"> Performance Disclaimer Text </h1>
                  
                  <textarea style="padding: 10px; font-size: 16px;" id="<?php echo $this->prefix ?>preformance-section-desc" rows="10"> <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "preformance-section-desc-data", true )) ?> </textarea>
                </div>

                <div class="template-tables-preview" id="<?php echo $this->prefix ?>distribution-detail">
                    <div style="display: flex;gap: 20px;align-items: center;">
                        <h1 style="font-weight: 600;"> Distribution Detail </h1>
                        <button id="add-dis-detail-row" type="button" class="button button-primary button-large">
                            <div> Add New Row  </div>
                        </button>
                        <div style="display: none;" id="dis-loading-show">  
                            <img style="width:32px; height:32px;" src="<?php echo dirname(plugin_dir_url(__FILE__ )) . '/admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation">
                        </div>
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4-icon"> 
                        <h3>EX-Date</h3> 
                        <h3>Record Date</h3>
                        <h3>Payable Date</h3> 
                        <h3>Amount</h3>
                        <h3>Rate Type</h3>
                        <h3></h3>
                    </div>
                    <div id="dis-detail-row-container"> <?php 
                        $current_data = get_post_meta( get_the_ID(), $this->prefix . 'disturbion-detail-data', true );
                        $current_data = $current_data == '' ? '[]' : $current_data;
                        $current_data_array = json_decode($current_data, true);
                        for ($i=0; $i < count($current_data_array); $i++) {  ?>
                            <div id="<?php echo the_title('','',false) . '-dis-' . $i ?>" style="padding: 10px 0;" class="table-horizontal-row-grid table-horizontal-row-grid-4-icon">
                                <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>dis-<?php echo $i; ?>-1" value="<?php echo $current_data_array[$i]['ex-date'] ?>" />
                                <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>dis-<?php echo $i; ?>-2" value="<?php echo $current_data_array[$i]['rec-date'] ?>" />
                                <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>dis-<?php echo $i; ?>-3" value="<?php echo $current_data_array[$i]['pay-date'] ?>" />
                                <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>dis-<?php echo $i; ?>-4" value="<?php echo $current_data_array[$i]['amount'] ?>" />

                                <select id="<?php echo $this->prefix ?>disturbion-detail-varcol-<?php echo $i; ?>-5" >
                                    <option value> None </option>
                                    <option value="oi" <?php if(array_key_exists("varcol",$current_data_array[$i]) && $current_data_array[$i]['varcol'] == "oi") echo "selected"; ?> > Ordinary Income </option>
                                    <option value="stcg" <?php if(array_key_exists("varcol",$current_data_array[$i]) && $current_data_array[$i]['varcol'] == "stcg") echo "selected"; ?> > Short-Term Capital Gains </option>
                                    <option value="ltcg" <?php if(array_key_exists("varcol",$current_data_array[$i]) && $current_data_array[$i]['varcol'] == "ltcg") echo "selected"; ?> > Long-Term Capital Gains </option>
                                </select>

                                <button class="del-dis-detail-row" data-count="<?php echo $i; ?>" type="button" style="border: none; background: inherit; cursor: pointer;">
                                    <svg style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="template-tables-preview" >
               
                    <h1 style="font-weight: 600;"> Top 10 Holdings </h1>
                    <p>  Data as of 
                        <span id="">  
                            <input type="date" style="width: 120px;" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>top-holding-update-date" value="<?php echo date('Y-m-d', strtotime(get_post_meta( get_the_ID(), $this->prefix . "top-holding-update-date-data", true ))); ?>" />
                        </span>
                    </p>
                    <div class="table-horizontal-row-grid"> 
                        <h3>% of Net Assets</h3>
                        <h3>Name</h3>
                        <h3>Ticker</h3>
                        <h3>CUSIP</h3>
                        <h3>Shares Held</h3>
                        <h3>Market Value</h3>
                    </div>
                    <div id="<?php echo $this->prefix ?>holdings-containers">
                    <?php 
                        $holding_data_raw = get_post_meta( get_the_ID(), $this->prefix . "top-holders-data", true ); 
                        $holding_data = json_decode($holding_data_raw, true);
                        if($holding_data){
                            $row_seq_inc = 0;
                            foreach ($holding_data as $index => $holding) { ?>
                                <div style="padding: 10px 0;" class="table-horizontal-row-grid"> 
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-1" value="<?php echo $holding['Weightings'] ?>" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-2" value="<?php echo $holding['SecurityName'] ?>" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-3" value="<?php echo $holding['StockTicker'] ?>" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-4" value="<?php echo $holding['CUSIP'] ?>" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-5" value="<?php echo $holding['Shares'] ?>" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $index ?>-6" value="<?php echo $holding['MarketValue'] ?>" />
                                </div> <?php 
                                $row_seq_inc = $row_seq_inc + 1; 
                            } 
                        }else{ 
                            for ($i=0; $i < 10; $i++) { ?>
                                <div style="padding: 10px 0;" class="table-horizontal-row-grid"> 
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-1" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-2" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-3" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-4" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-5" />
                                    <input type="text" class="fund-details-input-feilds" id="ETF-Pre-holding-<?php echo $i ?>-6" />
                                </div>
                      <?php }
                        } ?>
                    </div>
                </div>
             </div>            
            </div>
            <div id="<?php echo $this->prefix ?>popup-bottombar-container">
                <button id="<?php echo $this->prefix ?>popup-submit-button"> Save </button>
            </div>
        </div>
    </div>
</div>
<?php ?>


