<?php $sftp = \ETFsSFTP\SFTP::getInstance();
$sftp_config = $sftp->get_config();
?>
<div style="display: flex; justify-content: space-between; margin: 10px 0">
    <h1 style="margin: auto 0;"> ETF Settings </h1>
</div>
<div>
    <div>
        <div class="ETF-Pre-settings-container">
            <div class="ETF-Pre-form-input-contianer">
                <form class="ETF-Pre-form-sftp-contianer">
                    <h3 style="margin: 10px 0 30px 0;">SFTP Cycle Settings</h3>
                    <div>
                        <div class="ETF-Pre-input-toggle-text">
                            <h4 class="feilds-label-style">SFTP is <span id='ETF-Pre-toggle-state-text'> <?php echo ($sftp_config["Automate"] === "t") ? "on" : "off";?></span></h4>
                            <label style="margin: auto 0;" class="switch">
                                <input <?php echo ($sftp_config["Automate"] === "t") ? "checked " : ''; ?> id="ETFs-Pre-auto" type="checkbox" >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div> 
                    <div class="row-margin">
                        <div class="">
                        <label style="margin: auto 0;"><h4 class="feilds-label-style">Host</h4> </label>
                        <input style="width: 60%;" id="ETFs-Pre-host" type="text" value=<?php echo ($sftp_config["Host"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["Host"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Username</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-user" type="text" value=<?php echo ($sftp_config["User"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["User"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Password</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-pass" type="password" value=<?php echo ($sftp_config["Pass"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["Pass"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Port</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-port" type="text" value=<?php echo ($sftp_config["Port"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["Port"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Frequency</h4></label>
                            <select id="ETFs-Pre-freq">
                                <option <?php echo ($sftp_config["Timing"] === "hourly") ? "selected" : '' ; ?> value="hourly">hourly</option>
                                <option <?php echo ($sftp_config["Timing"] === "twicedaily") ? "selected" : '' ; ?> value="twicedaily">twice a day</option>
                                <option <?php echo ($sftp_config["Timing"] === "daily") ? "selected" : '' ; ?> value="daily">daily</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-margin">
                        <label style="margin: auto 0;"><h4 class="feilds-label-style">Last Successful Cycle</h4></label>
                        <span> <span style="font-weight: 600;" id="ETF-Pre-cycle-counter"> <?php echo $sftp_config["Last_Cycle_Timestamp"] === NULL ? 'None Yet' : $sftp_config["Last_Cycle_Timestamp"] ; ?>  </span> <span>(America/Chicago)</span> </span>
                    </div>
                    <div class="row-margin ">
                        <div class="btn-row-margin">
                            <a class="btn btn-success btn-lg save-button">Save</a>
                            <a class="cancel-button btn btn-danger btn-lg">Cancel</a>
                            <a class="btn btn-primary btn-lg edit-button">Edit</a>
                            <div class="btn status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                            <p style="margin: auto 0; cursor: auto;" class="btn" id="ETF-Pre-creds-state">  </p>
                        </div>
                    </div>
                </form>
            </div>
            <script> function drop_handler(event){ var nodeCopy = document.getElementById(event.dataTransfer.getData('text')).cloneNode(true); nodeCopy.id = nodeCopy.id + '-selected'; event.target.innerHTML = nodeCopy.innerText; } </script>
            <div class="ETF-Pre-form-input-contianer ETF-Pre-file-settings">
                <div style="width: 100%;" class="ETF-Pre-form-sftp-contianer">
                    <h3 style="margin: 10px 0 30px 0;">SFTP File Naming</h3>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Daily NAV (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-nav-name" > <?php echo $sftp_config["Nav"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-nav-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Holdings (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-holding-name" > <?php echo $sftp_config["Holding"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-holding-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Sec Yeild (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-sec-name" > <?php echo $sftp_config["Sec"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-sec-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Monlthy ROR (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-ror-name" > <?php echo $sftp_config["Ror"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-ror-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Daily Index (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-index-name" > <?php echo $sftp_config["Ind"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-ror-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin ">
                        <div class="btn-row row-margin">
                            <a class="btn btn-primary btn-lg edit-file-button">Edit</a>
                            <a class="btn btn-success btn-lg scan-dir-button">Scan</a>
                            <a class="btn btn-success btn-lg update-files-button">Save</a>
                            <a class="btn btn-success btn-lg cancel-file-button">Cancel</a>
                            <div class="btn status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation-file-settings" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                            <p style="margin: auto 0; cursor: auto;" class="btn" id="ETF-Pre-file-state">  </p>
                        </div>
                    </div>
                </div>

                <div style="width: 100%;">
                    <div style="display: flex; gap: 30px; justify-content: center; align-items: center;">
                        <p style="margin: auto 0;">List View</p>
                        <label class="switch">
                            <input id="ETFs-Pre-toggle-file-view" type="checkbox" >
                            <span class="slider round"></span>
                        </label>
                        <p style="margin: auto 0;">Grid View</p>
                    </div>
                    <div class="stage">
                        <div style='display: none;' id="ETFs-Pre-scaned-file-dir" class="folder-wrap level-current scrolling">
                            
                        </div>
                        <div id="ETFs-Pre-scaned-file-list-dir" class="folder-wrap level-current scrolling">
                            <ul>
                                <li class="root">
                                    Downloads/
                                </li>
                                <div id="ETFs-Pre-scaned-file-list-dirc"> </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ETF-Pre-form-input-contianer">
                <form class="ETF-Pre-form-sftp-contianer">
                    <?php include_once 'frontpage-box-layout.php' ?>
                </form>
            </div>
            <div class="ETF-Pre-form-input-contianer">
                <div class="ETF-Pre-form-sftp-contianer">
                    <?php 
                        $divz_values = get_option('divz-chart-values');
                        $divz_values_opt = json_decode($divz_values, true);
                    ?>
                    <h3 style="margin: 10px 0 30px 0;">DIVZ Charts</h3>
                    <div>
                        <div style="display: flex; justify-content: space-around; margin: 50px 0px;">
                            <div>
                                <h4 style="text-align: center;"># of Stocks</h4>
                                <div>
                                    <label style="margin: 0px 10px;" for="etfs-divz-no-stocks">
                                        DIVZ: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-divz-no-stocks" value="<?php echo $divz_values_opt['no_stocks']['divz'] ?>">
                                    </label>

                                    <label style="margin: 0px 10px;" for="etfs-sp-no-stocks">
                                        S&P 500: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-sp-no-stocks" value="<?php echo $divz_values_opt['no_stocks']['sp'] ?>">
                                    </label>
                                </div>
                            </div>
                            <div>
                                <h4 style="text-align: center;">P/S</h4>
                                <div>
                                    <label style="margin: 0px 10px;" for="etfs-divz-ps">
                                        DIVZ: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-divz-ps" value="<?php echo $divz_values_opt['ps']['divz'] ?>">
                                    </label>

                                    <label style="margin: 0px 10px;" for="etfs-sp-ps">
                                        S&P 500: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-sp-ps" value="<?php echo $divz_values_opt['ps']['sp'] ?>">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-around; margin: 50px 0px;">
                            <div>
                                <h4 style="text-align: center;">P/E</h4>
                                <div>
                                    <label style="margin: 0px 10px;" for="etfs-divz-pe">
                                        DIVZ: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-divz-pe" value="<?php echo $divz_values_opt['pe']['divz'] ?>">
                                    </label>

                                    <label style="margin: 0px 10px;" for="etfs-sp-pe">
                                        S&P 500: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-sp-pe" value="<?php echo $divz_values_opt['pe']['sp'] ?>">
                                    </label>
                                </div>
                            </div>
                            <div>
                                <h4 style="text-align: center;">P/B</h4>
                                <div>
                                    <label style="margin: 0px 10px;" for="etfs-divz-pb">
                                        DIVZ: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-divz-pb" value="<?php echo $divz_values_opt['pb']['divz'] ?>">
                                    </label>

                                    <label style="margin: 0px 10px;" for="etfs-sp-pb">
                                        S&P 500: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-sp-pb" value="<?php echo $divz_values_opt['pb']['sp'] ?>">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-around; margin: 50px 0px;">
                            <div>
                                <h4 style="text-align: center;">Average Market Cap</h4>
                                <div>
                                    <label style="margin: 0px 10px;" for="etfs-divz-avg">
                                        DIVZ: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-divz-avg" value="<?php echo $divz_values_opt['avg']['divz'] ?>">
                                    </label>

                                    <label style="margin: 0px 10px;" for="etfs-sp-avg">
                                        S&P 500: 
                                        <input type="number" step=".01" class="drop-file-name div-chart-input" style="width: 120px;" id="etfs-sp-avg" value="<?php echo $divz_values_opt['avg']['sp'] ?>">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-margin">
                    <div class="btn-row-margin">
                        <a class="btn btn-success btn-lg diz-save-button">Save</a>
                        <a class="diz-cancel-button btn btn-danger btn-lg">Cancel</a>
                        <a class="btn btn-primary btn-lg diz-edit-button">Edit</a>
                        <div class="btn diz-status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation-3" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                        <p style="margin: auto 0; cursor: auto;" class="btn" id="ETF-Pre-creds-state-3">  </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

