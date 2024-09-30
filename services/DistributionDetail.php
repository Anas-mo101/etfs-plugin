<?php 

namespace ETFsDisDetail;

class DisturbutionDetail{

    var $prefix = 'ETF-Pre-';
    var $meta_key = 'disturbion-detail-data';

    function __construct(){}

    function init(){
        add_shortcode('render-dis-rows-data', array($this, 'render_disturbion_row'));
    }

    function add_disturbion_row($etfName){
        $etf = sanitize_text_field( $etfName );
        $post_to_update = custom_get_page_by_title( $etf, OBJECT, 'etfs' );
        $current_data = get_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, true );

        $current_data_array = json_decode($current_data, true);

        $current_data_array[] = array( 'ex-date' => '', 'rec-date' => '', 'pay-date' => '', 'amount' => '', 'varcol' => '');
        $count_data = count($current_data_array);

        error_log($count_data);

        $new_data_array = json_encode($current_data_array);
        update_post_meta( $post_to_update->ID, $this->prefix . $this->meta_key, $new_data_array );
        

        return array(
            'success' => true,
            'newIndex' => $count_data,
            'data' => $current_data_array
        );
    }

    function delete_disturbion_row($etfName, $index){
        $success = true;
        $etf = sanitize_text_field( $etfName );
        $index = (int) sanitize_text_field( $index );

        $post_to_update = custom_get_page_by_title( $etf, OBJECT, 'etfs' );
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

        return array('success' => $success, 'data' => $current_data_array);
    }

    function render_disturbion_row(){
        ob_start();
        $current_data = get_post_meta( get_the_ID(), $this->prefix . 'disturbion-detail-data', true );
        $current_data = $current_data == '' ? '[]' : $current_data;
        $current_data_array = json_decode($current_data, true);

        $varcols = [ "oi" => "Ordinary Income", "stcg" => "Short-Term Capital Gains", "ltcg" => "Long-Term Capital Gains", "" => "-"]

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

        <table class="table-ts table-10" style="border-collapse: separate; display: table; overflow-x:auto; border-spacing: 0 17px; margin: 0;">
            <thead>
                <tr>
                    <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Ex-Date</th>
                    <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Record Date</th>
                    <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Payable Date</th>
                    <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;">Amount</th>
                    <th class="table-ts-title2 dynamic-elementor-font-style-body-bold" style="text-align: center;"> Rate Type </th>
                </tr>
            </thead>
            <tbody id="disturbionTableBody">
            
            </tbody>
        </table>

        <?php if (is_array($current_data_array) && count($current_data_array) >= 5) { ?>
            <style>
                <?php require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/css/button.css'; ?>
            </style>
            
            <div id="load-more-details" style="margin: 20px 0;">
                <a id="download_button_A_2">
                    <span id="download_button_SPAN_3">
                        <span id="download_button_SPAN_10"> Load More </span>
                    </span>
                </a>
            </div>
        <?php } ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const data = <?php echo json_encode($current_data_array); ?>;
                const rowsPerPage = 5;
                let currentPage = 1;

                function renderTable(page = 1) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    const paginatedData = data.slice(start, end);

                    const tableBody = document.getElementById('disturbionTableBody');

                    if (paginatedData.length === 0) {
                        tableBody.innerHTML = `<tr>
                            <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                            <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                            <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                            <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                            <td class="table-ts-in pb" style="text-align: center;padding: 5px 15px;"> - </td>
                        </tr>`;
                        return;
                    }

                    paginatedData.forEach(function(value) {
                        const varcol = value['varcol'] ?? '';
                        tableBody.innerHTML += ` <tr>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['ex-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['rec-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['pay-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['amount']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;">${varcol in <?= json_encode($varcols) ?> ? <?= json_encode($varcols) ?>[varcol] : '-'}</td>
                        </tr>`;
                    });
                }

                function renderPagination() {
                    const loadmore = document.getElementById('load-more-details');

                    if (!loadmore) {
                        return
                    }

                    const pageCount = Math.ceil(data.length / rowsPerPage);
                    loadmore.addEventListener('click', function() {
                        if(pageCount >= currentPage + 1){
                            currentPage = currentPage + 1;
                            renderTable(currentPage);

                            if(pageCount < currentPage + 1){
                                loadmore.style.display = "none";
                            }
                        }
                    });
                }

                renderTable(currentPage);
                renderPagination();
            });
        </script> <?php 

        return ob_get_clean();
    }
}