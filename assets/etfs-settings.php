<?php
$sftp = SFTP::getInstance();
$sftp_config = $sftp->get_config();
?>
<h1> ETFs Settings </h1>
<div>
    <div>
        <div class="ETF-Pre-settings-container">
            <div class="ETF-Pre-form-input-contianer">
                <form class="ETF-Pre-form-sftp-contianer">
                    <h3 style="margin: 10px 0 30px 0;">SFTP cycle settings</h3>
                    <div>
                        <div class="ETF-Pre-input-toggle-text">
                            <h4>SFTP is <span id='ETF-Pre-toggle-state-text'> <?php echo ($sftp_config["auto"] === "true") ? "on" : "off";?></span></h4>
                            <label class="switch">
                                <input <?php echo ($sftp_config["auto"] === "true") ? "checked" : '' ; ?>id="ETFs-Pre-auto" type="checkbox" >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div> 
                    <div class="row-margin">
                        <div class="">
                        <label><h4>Host:</h4> </label>
                        <input style="width: 60%;" id="ETFs-Pre-host" type="text" value=<?php echo ($sftp_config["host"] === "null") ? '"" placeholder="*"' : '"' . $sftp_config["host"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label><h4>Username:</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-user" type="text" value=<?php echo ($sftp_config["username"] === "null") ? '"" placeholder="*"' : '"' . $sftp_config["username"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label><h4>Password:</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-pass" type="password" value=<?php echo ($sftp_config["password"] === "null") ? '"" placeholder="*"' : '"' . $sftp_config["password"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label><h4>Port:</h4> </label>
                            <input style="width: 60%;" id="ETFs-Pre-port" type="text" value=<?php echo ($sftp_config["port"] === "null") ? '"" placeholder="*"' : '"' . $sftp_config["port"] . '"'; ?> />
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="">
                            <label><h4>Frequency</h4></label>
                            <select id="ETFs-Pre-freq">
                                <option <?php echo ($sftp_config["timing"] === "halfhour") ? "selected" : '' ; ?> value="halfhour">Every 30 minutes</option>
                                <option <?php echo ($sftp_config["timing"] === "onehour") ? "selected" : '' ; ?> value="onehour">Hourly</option>
                                <option <?php echo ($sftp_config["timing"] === "threehour") ? "selected" : '' ; ?> value="threehour">Every Three Hour</option>
                                <option <?php echo ($sftp_config["timing"] === "24hour") ? "selected" : '' ; ?> value="24hour">Daily</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-margin ">
                        <div class="btn-row-margin">
                            <a class="btn btn-success btn-lg save-button">Save</a>
                            <a class="cancel-button btn btn-danger btn-lg">Cancel</a>
                            <a class="btn btn-primary btn-lg edit-button">Edit</a>
                            <div class="btn status-states" style="display: none; margin: auto 0;" id="ETFs-Pre-loadinganimation" > <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(dirname( __FILE__ ) ). 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                        </div>
                    </div>
                </form>
            </div>
            <script> function drop_handler(event){ var nodeCopy = document.getElementById(event.dataTransfer.getData('text')).cloneNode(true); nodeCopy.id = nodeCopy.id + '-selected'; event.target.innerHTML = nodeCopy.innerText; } </script>
            <div class="ETF-Pre-form-input-contianer ETF-Pre-file-settings">
                <div style="width: 100%;" class="ETF-Pre-form-sftp-contianer">
                    <h3 style="margin: 10px 0 30px 0;">SFTP file naming settings</h3>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label><h4>Daily NAV (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-nav-name" > <?php echo $sftp_config["files"]["nav"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-nav-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label><h4>Holdings (csv):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-holding-name" > <?php echo $sftp_config["files"]["holding"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-holding-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label><h4>Distirbution Memo (pdf):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-dist-name" > <?php echo $sftp_config["files"]["dist"]; ?> </div>
                            <svg onclick="document.getElementById('ETFs-Pre-dist-name').innerHTML = ''" style="margin: auto 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill clear-set-file" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="row-margin">
                        <div class="ETF-Pre-label-input-grid">
                            <label><h4>Monlthy ROR (pdf):</h4> </label>
                            <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-ror-name" > <?php echo $sftp_config["files"]["ror"]; ?> </div>
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