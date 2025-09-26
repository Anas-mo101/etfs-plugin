<?php


$mp_ten = get_post_meta( get_the_ID(), "ETF-Pre-perf-mp-ten-year-data", true );
$nav_ten = get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-ten-year-data", true );
$sp_ten = get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-ten-year-data", true );

$mp_fifteen = get_post_meta( get_the_ID(), "ETF-Pre-perf-mp-fifteen-year-data", true );
$nav_fifteen = get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-fifteen-year-data", true );
$sp_fifteen = get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-fifteen-year-data", true );

$show = get_post_meta( get_the_ID(), "ETF-Pre-perf-ten-year-exist-data", true ) === "true";
$show_fiften = get_post_meta( get_the_ID(), "ETF-Pre-perf-fiften-year-exist-data", true ) === "true";

?>

<style>
    .table-horizontal-row-null {
        background-color: white;
        padding: 0 15px;
        display: grid;
        grid-template-columns: auto;
        width: 100%;
        margin: 10px 0;
        justify-items: center;
        justify-content: center;
    }

    .table-horizontal-row-text {
        color: #12223D;
        font-weight: 600;
        text-align: left;
        font-size: 25px;
        font-family: "Avenir Next", sans-serif;
        margin: 20px 0;
    }

    .vertical-aligned-table {
        vertical-align: bottom;
    }

    #pref-lg-table {
        border-collapse: separate;
        display: table;
        overflow-x: auto;
        border-spacing: 0 17px;
        margin: 0;
    }

    @media screen and (max-width:650px) {
        #pref-lg-table {
            display: block !important;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

<table id="pref-lg-table" class="table-ts table-10" >
    <tr>
        <th style="text-align: left; min-width: 200px; padding-left: 30px;border: none;background: inherit;" class="table-ts-in pb vertical-aligned-table dynamic-elementor-font-style-body-bold"></th>
        <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">3 Month</th>
        <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">6 Month</th>
        <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">1 Year</th>
        <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left; ">5 Year</th>

        <?php if ($show) { ?>
            <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">10 Year</th>
        <?php } ?>

        <?php if ($show_fiften) { ?>
            <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">15 Year</th>
        <?php } ?>

        <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">Since Inception</th>
    </tr>

    <tr>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left; padding: 5px 30px;"> Market Price </td>

        <td class="table-ts-in pb name-co dynamic-elementor-font-style-body" style="padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-market-three-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-market-six-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-market-year-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-market-five-year-data", true ) ?>
        </td>

        <?php if ( $show ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $mp_ten ?> </td>
        <?php } ?>

        <?php if ( $show_fiften ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $mp_fifteen ?> </td>
        <?php } ?>

        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-market-inception-data", true ) ?>
        </td>
    </tr>

    <tr style="pointer-events: none; background-color: #fff;">
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left; padding: 5px 30px;"> Fund NAV </td>
        <td class="table-ts-in pb name-co dynamic-elementor-font-style-body" style="padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-three-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-six-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-year-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-five-year-data", true ) ?>
        </td>
        <?php if ( $show ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $nav_ten ?> </td>
        <?php } ?>

        <?php if ( $show_fiften ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $nav_fifteen ?> </td>
        <?php } ?>

        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-nav-inception-data", true ) ?>
        </td>
    </tr>

    <tr>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left; padding: 5px 30px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-preformance-benchmark-label-data", true ) ?>
        </td>
        <td class="table-ts-in pb name-co dynamic-elementor-font-style-body" style="padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-three-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-six-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-year-data", true ) ?>
        </td>
        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-five-year-data", true ) ?>
        </td>

        <?php if ( $show ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $sp_ten ?> </td>
        <?php } ?>

        <?php if ( $show_fiften ) { ?>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;"> <?= $sp_fifteen ?> </td>
        <?php } ?>

        <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;padding: 5px 15px;">
            <?= get_post_meta( get_the_ID(), "ETF-Pre-perf-sp-inception-data", true ) ?>
        </td>
    </tr>

</table>