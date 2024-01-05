<?php 

namespace ETFsDisDetail;

class DisturbutionDetail{

    var $prefix = 'ETF-Pre-';
    var $meta_key = 'disturbion-detail-data';

    function __construct(){
        add_shortcode('render-dis-rows-data', array($this, 'render_disturbion_row'));
        add_action( 'wp_ajax_add_disturbion_row', array($this, 'add_disturbion_row'));
        add_action( 'wp_ajax_del_disturbion_row', array($this, 'delete_disturbion_row'));
    }

    function add_disturbion_row(){
        $etf = sanitize_text_field( $_POST['etfName'] );
        $post_to_update = get_page_by_title( $etf, OBJECT, 'etfs' );
        $current_data = get_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, true );

        $current_data_array = json_decode($current_data, true);

        $current_data_array[] = array( 'ex-date' => '', 'rec-date' => '', 'pay-date' => '', 'amount' => '' );
        $count_data = count($current_data_array);

        error_log($count_data);

        $new_data_array = json_encode($current_data_array);
        update_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, $new_data_array );
        

        $res = array(
            'success' => true,
            'newIndex' => $count_data,
            'data' => $current_data_array
        );
        wp_send_json($res);
    }

    function delete_disturbion_row(){
        $success = true;
        $etf = sanitize_text_field( $_POST['etfName']);
        $index = (int) sanitize_text_field( $_POST['index']);

        $post_to_update = get_page_by_title( $etf, OBJECT, 'etfs' );
        $current_data = get_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, true );
        $current_data_array = json_decode($current_data, true);

        if(isset($current_data_array[$index])){
            unset($current_data_array[$index]); 
            $current_data_array = array_values($current_data_array);
        }else{
            $success = false;
        }

        $new_data_array = json_encode($current_data_array);
        update_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, $new_data_array );

        $res = array('success' => $success, 'data' => $current_data_array);
        wp_send_json($res);
    }

    function render_disturbion_row(){
        ob_start();
        $current_data = get_post_meta( get_the_ID(), $this->prefix . 'disturbion-detail-data', true );
        $varcol = get_post_meta( get_the_ID(), $this->prefix . 'disturbion-detail-varcol-data', true );
        $current_data = $current_data == '' ? '[]' : $current_data;
        $current_data_array = json_decode($current_data, true);

        $varcols = [
            "oi" => "Ordinary Income",
            "stcg" => "Short-Term Capital Gains",
            "ltcg" => "Long-Term Capital Gains",
            "" => "",
        ]

        ?> <table class="table-ts table-10" style="border-collapse: separate; display: table; overflow-x:auto; border-spacing: 0 17px; margin: 0;">
        <tr>
            <?php 
                if($varcol && $varcol != ""){
                    ?> <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;"><?= $varcols[$varcol] ?></th> <?php
                }
            ?>
            <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Ex-Date</th>
            <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Record Date</th>
            <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Payable Date</th>
            <th style="text-align: center;" class="table-ts-title2 dynamic-elementor-font-style-body-bold">Amount</th>
        </tr> <?php 
        
        if(is_array($current_data_array) && count($current_data_array) > 0){
            ?> <style>
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

            <?php foreach ($current_data_array as $value) { 
                ?> <tr>
                    <?php 
                        if($varcol && $varcol != ""){
                            ?> <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;"><?= $value['varcol'] ?? "-" ?></td> <?php
                        }
                    ?>
                    <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;"><?php echo $value['ex-date'] ?></td>
                    <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;"><?php echo $value['rec-date'] ?></td>
                    <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;"><?php echo $value['pay-date'] ?></td>
                    <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;"><?php echo $value['amount'] ?></td>
                </tr> <?php
            } 
            echo '</table>' ;
        }else{
            ?> <tr>
                <?php 
                        if($varcol && $varcol != ""){
                            ?> <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td> <?php
                        }
                    ?>
                <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
            </tr>  </table> <?php
        }
        return ob_get_clean();
    }
}