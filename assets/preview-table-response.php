
<div id="<?php echo $this->prefix ?>popup-underlay">
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
 
                <div class="template-tables-preview" id="<?php echo $this->prefix ?>fund-data-pricing">
                    <input type="checkbox" id="<?php echo $this->prefix ?>fund-detials-section">
                    <label for="<?php echo $this->prefix ?>fund-detials-section"> Update Fund Details Section ? </label><br>

                    <h1> Fund Details </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>rate-date-fund-details">  </span></p>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3>Inception Date </h3> 
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>inc-date-previewform" value=" <?php echo htmlspecialchars(get_post_meta( get_the_ID(), $this->prefix . "inception-date-data", true )) ?> " />
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
                        <h3>  Ticker </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>ticker-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "ticker-data", true ) ) ?>" />
                        <h3> Expense Ratio </h3>
                        <input type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>expense-ratio-previewform" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "expense-raito-data", true ) ) ?>" />
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <div></div>
                        <div></div>
                        <h3>30 Day SEC Yield* </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>sec-yield"> </p>
                    </div>
                </div>

                <div class="template-tables-preview" style="width: 50%;" id="<?php echo $this->prefix ?>fund-data-pricing">
                    <input type="checkbox" id="<?php echo $this->prefix ?>fund-pricing-section">
                    <label for="<?php echo $this->prefix ?>fund-pricing-section"> Update Fund Data & Pricing Section ? </label><br>

                    <h1> Fund Data & Pricing </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>rate-date">  </span></p>
                    <div class="table-horizontal-row"> 
                        <h3>Net Assets as of <span id="<?php echo $this->prefix ?>rate-date-net"></h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>nav-assets">  </p>
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> NAV </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>nav-value">  </p>
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Shares Outstanding </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>shares-outstanding">  </p>
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Premium/Discount Percentage </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>premium-discount-percentage" >  </p>
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3> Closing Price </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>closing-price">  </p>
                    </div>
                    <div class="table-horizontal-row"> 
                        <h3>  Median 30 Day Spread Percentage </h3>
                        <p style="margin: auto 0;" id="<?php echo $this->prefix ?>median-spread-per">  </p>
                    </div>
                </div>

                <div class="template-tables-preview" style="width: 60%;">
                    <div style='margin-bottom: 10px;'>
                        <input type="checkbox" id="<?php echo $this->prefix ?>draw-graph-section">
                        <label for="<?php echo $this->prefix ?>draw-graph-section"> Update Graph Section ? </label><br>
                    </div>
                    <div id="<?php echo $this->prefix ?>graphcontainer"></div>
                </div>

                <div class="template-tables-preview" id="<?php echo $this->prefix ?>outcome-period">
                    <input type="checkbox" id="<?php echo $this->prefix ?>outcome-period-section">
                    <label for="<?php echo $this->prefix ?>outcome-period-section"> Update Outcome Period Values Section ? </label><br>

                    <h1> Outcome Period Values </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>outcome-period-update-date">  </span></p>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                        <h3>ETF Starting NAV/Period Return</h3> 
                        <h3>SPX Index Reference Price</h3>
                        <h3>Downside Buffer</h3>
                        <h3>Expected Upside Participation</h3> 
                        <h3>Days Remaining</h3>
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>etf-starting-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "etf-starting-return-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>spx-index-price" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "spx-index-price-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>downside-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "downside-buffer-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>expected-upside" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "expected-upside-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>days-remaining" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "days-remaining-data", true ) ) ?>" />
                    </div>
                </div>

                <div class="template-tables-preview" id="<?php echo $this->prefix ?>current-outcome-period">
                    <input type="checkbox" id="<?php echo $this->prefix ?>current-outcome-period-section">
                    <label for="<?php echo $this->prefix ?>current-outcome-period-section"> Update Current Outcome Period Values Section ? </label><br>

                    <h1> Current Outcome Period Values </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>current-outcome-period-update-date">  </span></p>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                        <h3>ETF Starting NAV/Period Return</h3> 
                        <h3>SPX Period Return</h3>
                        <h3>Remaining Buffer</h3>
                        <h3>Downside Before Buffer</h3> 
                        <h3>Remaining Outcome Period</h3>
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-5"> 
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-etf-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-etf-return-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-spx-return" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-spx-return-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-remaining-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-remaining-buffer-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-downside-buffer" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-downside-buffer-data", true ) ) ?>" />
                        <input style='margin: 15px 0;' type="text" class="fund-details-input-feilds" id="<?php echo $this->prefix ?>current-remaining-outcome" value="<?php echo htmlspecialchars( get_post_meta( get_the_ID(), $this->prefix . "current-remaining-outcome-data", true ) ) ?>" />
                    </div>
                </div>

                <div class="template-tables-preview" >
                    <input type="checkbox" id="<?php echo $this->prefix ?>performance-section">
                    <label for="<?php echo $this->prefix ?>performance-section"> Update Performance Section ? </label><br>

                    <h1> Performance </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>preformance-update-date">  </span></p>
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
                            <p id='<?php echo $this->prefix ?>market-price-three' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>market-price-six' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>market-price-year' style="margin: 20px 0;"> </p>
                            <p style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>market-price-inception' style="margin: 20px 0;"> </p>
                        </div>
                        <div class="table-horizontal-row-grid"> 
                            <p style="margin: 20px 0;"> Fund NAV </p>
                            <p id='<?php echo $this->prefix ?>nav-three' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>nav-six' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>nav-year' style="margin: 20px 0;"> </p>
                            <p style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>nav-inception' style="margin: 20px 0;"> </p>
                        </div>
                        <div class="table-horizontal-row-grid"> 
                            <p style="margin: 20px 0;"> S&P 500 </p>
                            <p id='<?php echo $this->prefix ?>sp-three' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>sp-six' style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>sp-year' style="margin: 20px 0;"> </p>
                            <p style="margin: 20px 0;"> </p>
                            <p id='<?php echo $this->prefix ?>sp-inception' style="margin: 20px 0;"> </p>
                        </div>
                    </div>
                </div>

                <div class="template-tables-preview" id="<?php echo $this->prefix ?>distribution-detail">
                    <input type="checkbox" id="<?php echo $this->prefix ?>distribution-detail-section">
                    <label for="<?php echo $this->prefix ?>distribution-detail-section"> Update Distribution Detail Section ? </label><br>

                    <h1> Distribution Detail </h1>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <h3>EX-Date</h3> 
                        <h3>Record Date</h3>
                        <h3>Payable Date</h3> 
                        <h3>Amount</h3>
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <p id="<?php echo $this->prefix ?>ex-date"> </p>
                        <p id="<?php echo $this->prefix ?>rec-date"> </p>
                        <p id="<?php echo $this->prefix ?>pay-date"> </p>
                        <p id="<?php echo $this->prefix ?>amount-date"> </p>
                    </div>
                    <div class="table-horizontal-row-grid table-horizontal-row-grid-4"> 
                        <p id="<?php echo $this->prefix ?>"> ?? </p>
                        <p id="<?php echo $this->prefix ?>"> ?? </p>
                        <p id="<?php echo $this->prefix ?>"> ?? </p>
                        <p id="<?php echo $this->prefix ?>"> ?? </p>
                    </div>
                </div>

                <div class="template-tables-preview" >
                    <input type="checkbox" id="<?php echo $this->prefix ?>top-holdings-section">
                    <label for="<?php echo $this->prefix ?>top-holdings-section"> Update Top 10 Holding Section ? </label><br>

                    <h1> Top 10 Holdings </h1>
                    <p>  Data as of <span id="<?php echo $this->prefix ?>top-holding-update-date">  </span></p>
                    <div class="table-horizontal-row-grid"> 
                        <h3>% of Net Assets</h3>
                        <h3>Name</h3>
                        <h3>Ticker</h3>
                        <h3>CUSIP</h3>
                        <h3>Shares Held</h3>
                        <h3>Market Value</h3>
                    </div>
                    <div id="<?php echo $this->prefix ?>holdings-containers"> </div>
                </div>

            </div>
            <div id="<?php echo $this->prefix ?>popup-bottombar-container">
                <button type="button" id="<?php echo $this->prefix ?>popup-submit-button"> Save </button>
            </div>
        </div>
    </div>
</div>


