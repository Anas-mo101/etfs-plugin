<?php

$custom_fields = array( 
    array(
        "name"          => "etf-full-name",
        "title"         => "ETF Fund Name",
        "description"   => "Requried for un-structured ETFs",
        "type"          => "text",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "google-nav-url",
        "title"         => "Daily NAV Sheet URL",
        "description"   => "",
        "type"          => "g_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "google-holding-url",
        "title"         => "Holding Report Sheet URL",
        "description"   => "",
        "type"          => "g_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "google-nav-url-toggle-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "google-holding-url-toggle-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-monthly-ror-url",
        "title"         => "Monthly ROR URL",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-disturbion-url",
        "title"         => "Distribution Memo URL",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "graph-json-data",
        "title"         => "Graph Json",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "graph-json-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "inception-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "cusip-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "primary-exchange-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "iopv-symbol-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "nav-sybmol-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "ticker-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "expense-raito-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "rate-date-fund-details-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "rate-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "fund-listing-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "fund-pricing-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "net-assets-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "na-v-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "shares-out-standig-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "discount-percentage-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "closing-price-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "thirty-day-median-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "sec-yeild-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pref-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-market-three-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-market-six-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-market-year-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-market-inception-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-nav-three-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-nav-six-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-nav-year-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-nav-inception-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-sp-three-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-sp-six-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-sp-year-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "perf-sp-inception-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "dis-rate-share-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "ex-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pay-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "rec-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "outcome-period-update-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "etf-starting-return-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "spx-index-price-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "downside-buffer-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "expected-upside-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "days-remaining-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-outcome-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-etf-return-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-spx-return-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-remaining-buffer-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-downside-buffer-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-remaining-outcome-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "outcome-period-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "current-outcome-period-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "top-holding-update-date-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "top-holders-data",
        "title"         => "",
        "description"   => "",
        "type"          => "hidden",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "sub-advisor-name",
        "title"         => "Sub-advisor Name",
        "description"   => "",
        "type"          => "select",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    )
);

$custom_pdf_fields = array( 
    array(
        "name"          => "pdf-investment-case",
        "title"         => "Investment Case",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-summary-prospectus",
        "title"         => "Summary Prospectus",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-prospectus",
        "title"         => "Prospectus",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-sai",
        "title"         => "SAI",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-annual-report",
        "title"         => "Annual Report",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-semi-annual-report",
        "title"         => "Semi-Annual Report",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-dlte",
        "title"         => "Demystifying Liquidity and Taxes in ETPS",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-brochure",
        "title"         => "Brochure",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-press-release",
        "title"         => "Press Release",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-part-f-soi-1",
        "title"         => "Part F Schedule of Investments One",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    ),
    array(
        "name"          => "pdf-part-f-soi-2",
        "title"         => "Part F Schedule of Investments Two",
        "description"   => "",
        "type"          => "pdf_url",
        "scope"         => array( "etfs" ),
        "capability"    => "edit_posts"
    )
);

$etfs_all = array('JANZ', 'FEBZ', 'MARZ', 'APRZ', 'MAYZ', 'JUNZ', 'JULZ', 'AUGZ', 'SEPZ', 'OCTZ', 'NOVZ', 'DECZ', 'DIVZ', 'LRNZ', 'ECOZ', 'FLDZ');

$etfs_structured = array('JANZ', 'FEBZ', 'MARZ', 'APRZ', 'MAYZ', 'JUNZ', 'JULZ', 'AUGZ', 'SEPZ', 'OCTZ', 'NOVZ', 'DECZ');

$etfs_unstructured = array('DIVZ', 'LRNZ', 'ECOZ', 'FLDZ');