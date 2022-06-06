<?php?>
<style>
    .table-horizontal-row-null{
        background-color: white;
        padding: 0 15px;
        display: grid;
        grid-template-columns: auto; 
        width: 100%;
        margin: 10px 0;
        justify-items: center;
        justify-content: center;
    }

    .table-horizontal-row-text{
        color: #12223D;
        font-weight: 600;
        text-align: left;
        font-size: 25px;
        font-family: "Avenir Next", sans-serif; 
        margin: 20px 0;
    }
</style>

<table class="table-ts table-10" style="border-collapse: separate; display: table; overflow-x:auto; border-spacing: 0 17px; margin: 0;">
  <tr>
    <th style="text-align: left; min-width: 200px; padding-left: 30px; "class="table-ts-title2">% of <br>Net Assets</th>
    <th class="table-ts-title2" style="text-align: left; padding-left: 28px; min-width: 330px;">Name</th>
    <th class="table-ts-title2" style="text-align: left; padding-left: 28px;">Ticker</th>
    <th class="table-ts-title2" style="text-align: left; padding-left: 28px;">CUSIP</th>
    <th class="table-ts-title2" style="text-align: left; padding-left: 28px;">Shares Held</th>
    <th style="text-align: left; padding-left: 28px;" class="table-ts-title2">Market Value</th>
  </tr>

<?php $top_holdings_string_json = get_post_meta( get_the_ID(), "ETF-Pre-top-holders-data", true );
$top_holdings_array = json_decode($top_holdings_string_json, true);
if(empty($top_holdings_array)){ 
    echo '</table> <div class="table-horizontal-row-null">  <p class="table-horizontal-row-text"> Information not provided for current ETF </p> </div>';
}else{
    foreach ($top_holdings_array as $key => $holding) {  ?>
        <tr>
            <td class="table-ts-in pb" style="text-align: left; padding-left: 30px;"> <?php echo $holding["Weightings"]; ?> </td>
            <td class="table-ts-in pb name-co"> <?php echo $holding["SecurityName"]; ?> </td>
            <td class="table-ts-in pb" style="text-align: left;"> <?php echo $holding["StockTicker"]; ?> </td>
            <td class="table-ts-in pb" style="text-align: left;"> <?php echo $holding["CUSIP"]; ?> </td>
            <td class="table-ts-in pb" style="text-align: left;"> <?php echo $holding["Shares"]; ?> </td>
            <td class="table-ts-in pb"> <?php echo $holding["MarketValue"]; ?> </td>
        </tr>
    <?php }
    echo '</table>' ;
}