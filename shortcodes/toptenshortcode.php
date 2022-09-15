<?php?>
<style>
    .table-horizontal-row-null{ background-color: white; padding: 0 15px; display: grid; grid-template-columns: auto; width: 100%; margin: 10px 0; justify-items: center; justify-content: center;}
    .table-horizontal-row-text{ color: #12223D; font-weight: 600; text-align: left; font-size: 25px; font-family: "Avenir Next", sans-serif; margin: 20px 0;}
    .vertical-aligned-table{ vertical-align: bottom; }
</style>

<table class="table-ts table-10" style="border-collapse: separate; display: table; overflow-x:auto; border-spacing: 0 17px; margin: 0;">
  <tr>
    <th style="text-align: left; min-width: 200px; padding-left: 30px;border: none;background: inherit;" class="table-ts-in pb vertical-aligned-table dynamic-elementor-font-style-body-bold">% of Net Assets</th>
    <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left; min-width: 330px;">Name</th>
    <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">Ticker</th>
    <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left; ">CUSIP</th>
    <th class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table" style="background: inherit;border: none;text-align: left;">Shares Held</th>
    <th style="text-align: left; border: none;background: inherit;" class="table-ts-in pb dynamic-elementor-font-style-body-bold vertical-aligned-table">Market Value</th>
  </tr>

<?php $top_holdings_string_json = get_post_meta( get_the_ID(), "ETF-Pre-top-holders-data", true );
$top_holdings_array = json_decode($top_holdings_string_json, true);
if(empty($top_holdings_array)){ 
    echo '</table> <div class="table-horizontal-row-null">  <p class="table-horizontal-row-text"> No Records </p> </div>';
}else{
    foreach ($top_holdings_array as $key => $holding) {  ?>
        <tr>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left; padding-left: 30px;"> <?php echo $holding["Weightings"]; ?> </td>
            <td class="table-ts-in pb name-co dynamic-elementor-font-style-body-bold"> <?php echo $holding["SecurityName"]; ?> </td>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;"> <?php echo $holding["StockTicker"]; ?> </td>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;"> <?php echo $holding["CUSIP"]; ?> </td>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;"> <?php echo $holding["Shares"]; ?> </td>
            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: left;"> <?php echo $holding["MarketValue"]; ?> </td>
        </tr>
    <?php }
    echo '</table>' ;
}