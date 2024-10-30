<?php

namespace ETFsDisDetail;

class DisturbutionDetail
{

    var $prefix = 'ETF-Pre-';
    var $meta_key = 'disturbion-detail-data';

    function __construct() {}

    function init()
    {
        add_shortcode('render-dis-rows-data', array($this, 'render_disturbion_row'));
    }

    function add_disturbion_row($etfName)
    {
        $etf = sanitize_text_field($etfName);
        $post_to_update = custom_get_page_by_title($etf, OBJECT, 'etfs');
        $current_data = get_post_meta($post_to_update->ID, $this->prefix . $this->meta_key, true);

        $current_data_array = json_decode($current_data, true);

        $current_data_array[] = array('ex-date' => '', 'rec-date' => '', 'pay-date' => '', 'amount' => '', 'varcol' => '');
        $count_data = count($current_data_array);


        $new_data_array = json_encode($current_data_array);
        update_post_meta($post_to_update->ID, $this->prefix . $this->meta_key, $new_data_array);

        return array(
            'success' => true,
            'newIndex' => $count_data,
            'data' => $current_data_array
        );
    }

    function delete_disturbion_row($etfName, $index)
    {
        $success = true;
        $etf = sanitize_text_field($etfName);
        $index = (int) sanitize_text_field($index);

        $post_to_update = custom_get_page_by_title($etf, OBJECT, 'etfs');
        $current_data = get_post_meta($post_to_update->ID, $this->prefix . $this->meta_key, true);
        $current_data_array = json_decode($current_data, true);

        if (isset($current_data_array[$index])) {
            unset($current_data_array[$index]);
            $current_data_array = array_values($current_data_array);
        } else {
            $success = false;
        }

        $new_data_array = json_encode($current_data_array);
        update_post_meta($post_to_update->ID, $this->prefix . $this->meta_key, $new_data_array);

        return array('success' => $success, 'data' => $current_data_array);
    }

    function render_disturbion_row()
    {
        ob_start();
        $current_data = get_post_meta(get_the_ID(), $this->prefix . 'disturbion-detail-data', true);
        $current_data = $current_data == '' ? '[]' : $current_data;
        $current_data_array = json_decode($current_data, true);

        $varcols = ["oi" => "Ordinary Income", "stcg" => "Short-Term Capital Gains", "ltcg" => "Long-Term Capital Gains", "" => "-"]

?> <style>
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
                <?php require_once plugin_dir_path(dirname(__FILE__)) . 'admin/css/button.css'; ?>
                
                .loadmore {
                    margin: 20px 0;
                    display: block;
                    max-width: 200px;
                }

                .loadmore div {
                    display: flex;
                    flex-direction: row-reverse;
                    min-width: 200px;
                }

                .loadmore div svg path{
                    fill: rgb(99, 213, 211);
                }

                .loadmore:hover > div > p{
                    color: #0D031E !important;
                }

                .loadmore:hover > div > svg > path{
                    fill: #0D031E  !important;
                }

                .loadmore-mobile {
                    justify-content: center;
                    display: none;
                }

                .loadmoretext{ 
                    width: auto !important;
                    margin-top: 5px;
                }

                @media only screen and (min-width: 768px) {
                    .loadmore-mobile {
                        display: none;
                    }

                    .loadmore-nonmobile {
                        display: flex;
                    }
                }

                @media only screen and (max-width: 767px) {
                    .loadmore-mobile {
                        display: flex;
                    }

                    .loadmore-nonmobile {
                        display: none;
                    }
                }
            </style>

            <a id="loadmore-button" class="loadmore loadmore-mobile">
                <div>
                    <p id="download_button_SPAN_10" class="loadmoretext"> Show All </p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34">
                        <path d="M9.501 13.165a1.417 1.417 0 0 0-2 0 1.417 1.417 0 0 0 0 2l8.5 8.5a1.417 1.417 0 0 0 1.959.043l8.5-7.792a1.417 1.417 0 0 0 .087-2 1.417 1.417 0 0 0-2-.087l-7.5 6.875Z"></path>
                    </svg>
                </div>
            </a>
        <?php } ?>

        <section class="elementor-section elementor-inner-section elementor-element elementor-element-619dcf3 elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="619dcf3" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-d3a2e19" data-id="d3a2e19" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-6775a07 elementor-align-left bt-download elementor-tablet-align-center elementor-widget elementor-widget-button" data-id="6775a07" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper" style="display: flex; justify-content: space-between; align-items: center;">
                                    <a class="elementor-button elementor-button-link elementor-size-xs" href="https://content.true-shares.com/hubfs/Fund%20Documents/DIVZ/DIVZ%20Premium%20Discount.pdf" target="_blank" download="Premium Discount Information">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34">
                                                    <path d="M0 0h34v34H0Z" fill="none"></path>
                                                    <rect width="2" height="20" rx="1" transform="translate(16.099 4)" opacity=".3"></rect>
                                                    <path d="M9.501 13.165a1.417 1.417 0 0 0-2 0 1.417 1.417 0 0 0 0 2l8.5 8.5a1.417 1.417 0 0 0 1.959.043l8.5-7.792a1.417 1.417 0 0 0 .087-2 1.417 1.417 0 0 0-2-.087l-7.5 6.875Z"></path>
                                                    <rect width="26" height="3" rx="1.5" transform="translate(4.099 27)" opacity=".3"></rect>
                                                </svg> </span>
                                            <span class="elementor-button-text">DOWNLOAD PREMIUM DISCOUNT INFORMATION</span>
                                        </span>
                                    </a>

                                    <a id="loadmore-button" class="loadmore loadmore-nonmobile">
                                        <div>
                                            <p id="download_button_SPAN_10" class="loadmoretext"> Show All </p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34">
                                                <path d="M9.501 13.165a1.417 1.417 0 0 0-2 0 1.417 1.417 0 0 0 0 2l8.5 8.5a1.417 1.417 0 0 0 1.959.043l8.5-7.792a1.417 1.417 0 0 0 .087-2 1.417 1.417 0 0 0-2-.087l-7.5 6.875Z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const data = <?php echo json_encode($current_data_array); ?>;
                let showAllToggled = false;
                let currentPage = 1;

                function renderTable(page = 1, rowsPerPage = 5) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    const paginatedData = data.slice(start, end);
                    const tableBody = document.getElementById('disturbionTableBody');
                    tableBody.innerHTML = "";

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
                        tableBody.innerHTML += `<tr>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['ex-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['rec-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['pay-date']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;padding: 5px 15px;">${value['amount']}</td>
                            <td class="table-ts-in pb dynamic-elementor-font-style-body" style="text-align: center;">${varcol in <?= json_encode($varcols) ?> ? <?= json_encode($varcols) ?>[varcol] : '-'}</td>
                        </tr>`;
                    });
                }

                function renderPagination() {
                    const buttons = document.querySelectorAll('.loadmore');

                    if (buttons.length <= 0) {
                        return
                    }

                    buttons.forEach((loadmore) => {
                        loadmore.addEventListener('click', function() {
                            if (showAllToggled) {
                                showAllToggled = false;
                                renderTable(1)

                                document.querySelectorAll('.loadmoretext').forEach((loadmoreText) => {
                                    loadmoreText.innerHTML = "Show All";
                                    loadmoreText.style.color = "#63d5d3";
                                });

                                document.querySelectorAll('.loadmore div svg').forEach((icon) => {
                                    icon.style.transform = "rotate(0deg)";
                                });

                                document.querySelectorAll('.loadmore div svg path').forEach((icon) => {
                                    icon.style.fill = "#63d5d3";
                                });
                            } else {
                                showAllToggled = true;
                                renderTable(1, <?= count($current_data_array); ?>);

                                document.querySelectorAll('.loadmoretext').forEach((loadmoreText) => {
                                    loadmoreText.innerHTML = "Show Less";
                                    loadmoreText.style.color = "#0D031E";
                                });

                                document.querySelectorAll('.loadmore div svg').forEach((icon) => {
                                    icon.style.transform = "rotate(180deg)";
                                });

                                document.querySelectorAll('.loadmore div svg path').forEach((icon) => {
                                    icon.style.fill = "#0D031E";
                                });
                            }
                        });
                    });
                }

                renderTable(currentPage);
                renderPagination();
            });
        </script> <?php

                    return ob_get_clean();
                }
            }
