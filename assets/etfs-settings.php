<?php
/**
 * Control ETFs general settings (ui)
 */

$sftp = SFTP::getInstance();
$sftp_config = $sftp->get_config();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo plugin_dir_url(dirname( __FILE__)). 'admin/css/'?>SettingStyling.css">
<script src="<?php echo plugin_dir_url(dirname( __FILE__)). 'admin/js/settingsConfig.js'?>"></script>

<h1 style="margin: 20px 0;"> ETFs Settings </h1>
    <div>
        <div>
            <div class="ETF-Pre-settings-container">
                <div class="ETF-Pre-form-input-contianer">
                    <form id="ETF-Pre-form-sftp-contianer">
                        <h3 style="margin: 10px 0;">SFTP settings</h3>
                        <div>
                            <div class="ETF-Pre-input-toggle-text">
                                <h4>SFTP is on</h4>
                                <label class="switch">
                                    <input <?php echo ($sftp_config["auto"] === "true") ? "checked" : '' ; ?> id="ETFs-Pre-auto" type="checkbox" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div> 
                        <div class="">
                            <div class="">
                            <label><h4>Host:</h4> </label>
                            <input id="ETFs-Pre-host" type="text" placeholder="<?php echo ($sftp_config["host"] === "null") ? '*' : $sftp_config["host"]; ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <label><h4>Username:</h4> </label>
                                <input id="ETFs-Pre-user" type="text" placeholder="<?php echo ($sftp_config["username"] === "null") ? '*' : $sftp_config["username"]; ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <label><h4>Password:</h4> </label>
                                <input id="ETFs-Pre-pass" type="text" placeholder="<?php echo ($sftp_config["password"] === "null") ? '*' : $sftp_config["password"]; ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <label><h4>Port:</h4> </label>
                                <input id="ETFs-Pre-port" type="text" placeholder="<?php echo ($sftp_config["port"] === "null") ? '*' : $sftp_config["port"]; ?>" />
                            </div>
                        </div>
                        <div class="row">
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
                        <div class="row ">
                            <div class="btn-row">
                                <a class="btn btn-success btn-lg save-button">Save</a>
                                <a class="cancel-button btn btn-danger btn-lg">Cancel</a>
                                <a class="btn btn-primary btn-lg edit-button">Edit</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
