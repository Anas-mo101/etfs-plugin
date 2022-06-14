<?php
/**
 * Control ETFs general settings (ui)
 */

$sftp = SFTP::getInstance();
$sftp_config = $sftp->get_config();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo plugin_dir_url(dirname( __FILE__)). 'admin/css/'?>SettingStyling.css">
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
<script src="<?php echo plugin_dir_url(dirname( __FILE__)). 'admin/js/settingsConfig.js'?>"></script>

<h1> ETFs Settings </h1>
    <div>
        <div>
            <div class="ETF-Pre-settings-container">
                <div class="ETF-Pre-form-input-contianer">
                    <form class="ETF-Pre-form-sftp-contianer">
                        <h3 style="margin: 10px 0 30px 0;">SFTP cycle settings</h3>
                        <div>
                            <div class="ETF-Pre-input-toggle-text">
                                <h4>SFTP is <span id='ETF-Pre-toggle-state-text'> <?php echo ($sftp_config["auto"] === "true") ? "on" : "off";?> </span></h4>
                                <label class="switch">
                                    <input <?php echo ($sftp_config["auto"] === "true") ? "checked" : '' ; ?> id="ETFs-Pre-auto" type="checkbox" >
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
                <script>

                    function drop_handler(event){
                        var nodeCopy = document.getElementById(event.dataTransfer.getData('text')).cloneNode(true);
                        nodeCopy.id = nodeCopy.id + '-selected';
                        event.target.innerHTML = '';
                        event.target.appendChild(nodeCopy);
                    }

                </script>
                <div class="ETF-Pre-form-input-contianer ETF-Pre-file-settings">
                    <div style="width: 100%;" class="ETF-Pre-form-sftp-contianer">
                        <h3 style="margin: 10px 0 30px 0;">SFTP file naming settings</h3>
                        <div class="row-margin">
                            <div class="ETF-Pre-label-input-grid">
                                <label><h4>Daily NAV (csv):</h4> </label>
                                <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-nav" > </div>
                            </div>
                        </div>
                        <div class="row-margin">
                            <div class="ETF-Pre-label-input-grid">
                                <label><h4>Holdings (csv):</h4> </label>
                                <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-holdings-name" > </div>
                            </div>
                        </div>
                        <div class="row-margin">
                            <div class="ETF-Pre-label-input-grid">
                                <label><h4>Distirbution Memo (pdf):</h4> </label>
                                <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-dist-memo-name" > </div>
                            </div>
                        </div>
                        <div class="row-margin">
                            <div class="ETF-Pre-label-input-grid">
                                <label><h4>Monlthy ROR (pdf):</h4> </label>
                                <div class="drop-file-name" ondrop="drop_handler(event)" ondragover="event.preventDefault()" id="ETFs-Pre-monthly-name" > </div>
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
                        <div class="stage">
                            <div id="ETFs-Pre-scaned-file-dir" class="folder-wrap level-current scrolling">
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



