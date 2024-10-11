<?php

$dynamic = new DynamicProductsTable();
$tables = $dynamic->list_tables();

?>

<div style="display: flex; justify-content: space-between; margin: 10px 20px 10px 0px">
    <h1 style="margin: auto 0;"> Products Table </h1>
    <button class="button button-primary button-large" id="ETF-Pre-add-table-button"> Add Table </button>
</div>
<div>
    <div>
        <div class="ETF-Pre-settings-container">
            <?php
            for ($i = 0; $i < count($tables); $i++) {
                $table = $tables[$i];

                if (
                    !isset($table["TableData"]) ||
                    $table["TableData"] === null ||
                    empty($table["TableData"])
                ) {
                    $table["TableData"] = "[]";
                }

                $data = json_decode($table["TableData"], true);
            ?>
                <div
                    id="<?= $table["id"] ?>-section"
                    data-connection="<?= $table["ConnectionId"] ?>"
                    data-order="<?= $table["Torder"] ?>"
                    data-filename="<?= $table["FileName"] ?>"
                    data-name="<?= $table["Name"] ?>"
                    style="border: solid 2px #e6e6e6; padding: 0px 20px;">
                    <div style="display: flex; width: 100%; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 10px 0 30px 0;">Table Name: <?= $table["Name"] ?> </h3>
                        <div style="display: flex; gap: 10px;">
                            <button data-table="<?= $table["id"] ?>" class="button button-primary button-large delete-table" style="height: 30px"> Delete Table </button>
                            <button data-table="<?= $table["id"] ?>" class="button button-primary button-large edit-table" style="height: 30px"> Edit Table </button>
                            <button data-table="<?= $table["id"] ?>" class="button button-primary button-large show-table" style="height: 30px"> Toggle </button>
                        </div>
                    </div>
                    <table id="table-section-<?= $table["id"] ?>" style="display: none; overflow: auto;" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <?php
                                if (!empty($data)) {
                                    $firstRow = $data[0];
                                    foreach ($firstRow as $columnName => $value) {
                                        echo "<th>" . htmlspecialchars($columnName) . "</th>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $row) {
                                echo "<tr>";
                                foreach ($row as $cell) {
                                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="ETF-Pre-general-popup-underlay" id="ETF-Pre-popup-underlay-new-table-field">
        <div style="min-width: 300px; height: 85%;" id="ETF-Pre-popup-container">
            <div id="ETF-Pre-table-popup">
                <div id="ETF-Pre-popup-topbar-container">
                    <div style="font-weight: bold; display: flex;" id="ETF-Pre-popup-title-container">
                        <h2 id="ETF-Pre-popup-title"> Add New Table </h2>
                        <div class="btn status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation"> <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                    </div>
                    <button type="button" id="ETF-Pre-popup-close-button" onclick="closeForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
                <div style="overflow: auto; background-color: #ffffff;" id="ETF-Pre-popup-table-row-container">
                    <script>
                        function drop_handler(event) {
                            const nodeCopy = document.getElementById(event.dataTransfer.getData('text')).cloneNode(true);
                            nodeCopy.id = nodeCopy.id + '-selected';
                            event.target.innerHTML = nodeCopy.dataset.file;

                            event.target.dataset.file = nodeCopy.dataset.file;
                            event.target.dataset.connection = nodeCopy.dataset.connection;
                        }
                    </script>
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="ETF-Pre-popup-table-inner-container">
                        <label for="ETF-Pre-new-fund-field-doc"><b> Table Name* </b></label>
                        <input type="text" id="new-table-name" style="width: 100%;" />
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="ETF-Pre-popup-table-inner-container">
                        <label for="ETF-Pre-new-fund-field-doc"><b> Table Order* </b></label>
                        <select style="width: 100%; max-width: unset;" id="new-table-order">
                            <option> select order </option>
                            <?php
                            for ($i = 1; $i <= count($tables) + 1; $i++) {
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="ETF-Pre-popup-table-inner-container">
                        <label for="ETF-Pre-new-fund-field-doc"><b> Table Connection* </b></label>
                        <select style="width: 100%; max-width: unset;" id="new-table-connection">
                            <option> select sftp connection </option>
                            <?php
                                $connections_services = new \ConnectionServices();
                                $connections = $connections_services->list_connections();
                                for ($i = 0; $i < count($connections); $i++) {
                                    $connection = $connections[$i];
                                    echo "<option value='" . $connection["id"] . "'>" . $connection["Name"] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="ETF-Pre-popup-table-inner-container">
                        <label for="ETF-Pre-new-fund-field-doc"><b> File Name* (drag n drop file required file from list below) </b></label>
                        <div id="new-table-file" style="width: 100%;" class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()"> </div>
                    </div>
                    <div class="ETF-Pre-connections-container">
                        <div class="ETF-Pre-connection-container" style="margin-right: 0px; padding: 20px;">
                            <div id="ETF-Pre-table-collapse" class="ETF-Pre-table-collapse">
                                <div id="ETFs-Pre-scaned-file-list-dir" class="folder-wrap level-current scrolling">
                                    <ul>
                                        <li class="root"> Downloads/ </li>
                                        <div class="file-container" data-connection id="ETFs-Pre-scaned-file-list-dirc"></div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="ETF-Pre-popup-bottombar-container" style="flex-direction: row; justify-content: space-between;">
                    <div id="table-errors">

                    </div>

                    <button class="button button-primary button-large" type="buttton" id="table-submit-button"> Confirm </button>
                    <button class="button button-primary button-large" style="display: none;" type="buttton" id="table-update-button"> Update </button>
                </div>
            </div>
        </div>
    </div>

    <div class="ETF-Pre-general-popup-underlay" id="ETF-Pre-popup-underlay-del-table-field">
        <div style="width: 35%; height: 35%;min-width: 300px;" id="ETF-Pre-popup-container">
            <div id="ETF-Pre-table-popup">
                <div style="flex-direction: row-reverse;" id="ETF-Pre-popup-topbar-container">
                    <button type="button" id="ETF-Pre-popup-close-button" onclick="closeForm()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
                <div style="overflow: auto; background-color: #ffffff;" id="ETF-Pre-popup-table-container">
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; margin: auto;" id="ETF-Pre-popup-table-inner-container">
                        <label for="ETF-Pre-new-fund-field-doc"><b> Delete Table ? </b></label>
                        <div class="btn status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation"> <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                    </div>
                </div>
                <div id="ETF-Pre-popup-bottombar-container">
                    <button class="button button-primary button-large" type="buttton" id="table-delete-button"> Delete </button>
                </div>
            </div>
        </div>
    </div>
</div>