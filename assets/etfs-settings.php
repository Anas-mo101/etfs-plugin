<?php $sftp = SFTP::getInstance();
$sftp_config = $sftp->get_config();?>

<div style="display: flex; justify-content: space-between; margin: 10px 0">
    <h1 style="margin: auto 0;"> ETFs Settings </h1>
    <div class="ETF-Pre-counter-container">
        <p style="text-align: center;"> America/Chicago </p>
        <div class="ETF-Pre-counter-sub-container">
            <h3 id="ETF-Pre-cycle-counter"> <?php echo $sftp_config["Last_Cycle_Timestamp"] === NULL ? 'None Yet' : $sftp_config["Last_Cycle_Timestamp"] ; ?> </h3>
        </div>
        <p style="text-align: center;"> Last successful cycles </p>
    </div>
</div>
<div>
    <div>
        <div class="ETF-Pre-settings-container">
            <div class="ETF-Pre-form-input-contianer">
                <form class="ETF-Pre-form-sftp-contianer">
                    <h3 style="margin: 10px 0 30px 0;">SFTP cycle settings</h3>
                    <div>
                        <div class="ETF-Pre-input-toggle-text">
                            <h4 class="feilds-label-style">SFTP is <span id='ETF-Pre-toggle-state-text'> <?php echo ($sftp_config["Automate"] === "true") ? "on" : "off";?></span></h4>
                            <label style="margin: auto 0;" class="switch">
                                <input <?php echo ($sftp_config["Automate"] === "t") ? "checked " : ''; ?> id="ETFs-Pre-auto" type="checkbox" >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div> 
                    <div class="row-margin">
                        <div class="">
                        <label style="margin: auto 0;"><h4 class="feilds-label-style">Host:</h4> </label>
                        <input style="width: 60%;" id="ETFs-Pre-host" type="text" value=<?php echo ($sftp_config["Host"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["Host"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Username:</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-user" type="text" value=<?php echo ($sftp_config["User"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["User"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Password:</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-pass" type="password" value=<?php echo ($sftp_config["Pass"] === "*") ? '"" placeholder="*"' : '"' . $sftp_config["Pass"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Port:</h4> </label>
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
                    <h3 style="margin: 10px 0 30px 0;">SFTP file naming</h3>
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
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Distirbution Memo (pdf):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-dist-name" > <?php echo $sftp_config["Dist"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-dist-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label style="margin: auto 0;"><h4 class="feilds-label-style">Monlthy ROR (pdf):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-ror-name" > <?php echo $sftp_config["Ror"]; ?> </div>
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
                        <p style="margin: auto 0;">List Veiw</p>
                        <label class="switch">
                            <input id="ETFs-Pre-toggle-file-view" type="checkbox" >
                            <span class="slider round"></span>
                        </label>
                        <p style="margin: auto 0;">Grid Veiw</p>
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
        </div>
    </div>
</div>

