<?php


class CustomFeilds
{
    var $prefix = "ETF-Pre-";
    var $postTypes = array("etfs");

    function __construct()
    {
        add_action('admin_menu', array($this, 'create_custom_fields'));
        add_action('save_post', array($this, 'save_custom_fields'), 1, 2);
        add_action('do_meta_boxes', array($this, 'remove_default_custom_fields'), 10, 3);
    }

    /**
     * Remove the default Custom Fields meta box
     */
    function remove_default_custom_fields($type, $context, $post)
    {
        foreach (array('normal', 'advanced', 'side') as $context) {
            foreach ($this->postTypes as $postType) {
                remove_meta_box('postcustom', $postType, $context);
            }
        }
    }

    /**
     * Create the new Custom Fields meta box
     */
    function create_custom_fields()
    {
        if (function_exists('add_meta_box')) {

            if (!isset($_GET["post"]) || !isset($_GET["action"]) || $_GET["action"] !== "edit") {
                return;
            }


            $connection_id = get_post_meta($_GET["post"], $this->prefix . "connection-id", false);
            
            $connections_services = new \ConnectionServices();
            $config = $connections_services->get_config_db( (int) $connection_id );

            if ( !isset($config["Automate"]) || !isset($config["ActiveCycle"]) ) {
                $config["Automate"] = "f";
                $config["ActiveCycle"] = "f";
            }

            $state = $config['Automate'] === 't' ? 'SFTP enabled' : 'SFTP disabled';
            $color = $config['Automate'] === 't' ? 'green' : 'red';
            $cycle_state = $config['ActiveCycle'] === 't' ? '(The SFTP Cycle is being updated. Please do not make any changes to ETF pages at this time)' : '';
            $cycle_color = $config['ActiveCycle'] === 't' ? 'red' : 'black';

            foreach ($this->postTypes as $postType) {
                add_meta_box(
                    'my-custom-fields',
                    '<span style="color: ' . $cycle_color . ';"> ETF Settings ' . $cycle_state . '</span>  
                    <div style="display: flex; justify-content: flex-end;">
                        <span style="display: flex;">
                            <h4 style="margin: auto 15px;">  ' . $state . ' </h4>
                            <span style="margin: auto 0;">
                                <div style="background-color: ' . $color . ';border: solid 1px;border-radius: 50%;width: 15px;margin: auto 0px;height: 15px;"></div> 
                            </span>
                        </span>
                    </div>',
                    array($this, 'display_custom_fields'),
                    $postType,
                    'normal',
                    'high'
                );
            }
        }
    }

    /**
     * Display the new Custom Fields meta box
     */
    function display_custom_fields()
    {
        global $post;
        if ($post->post_type == "etfs") {
            include_once plugin_dir_path(dirname(__FILE__)) . 'view/modal.php';

            create_custom_efts_fields($this->prefix, '2', 'my-custom-fields'); ?>

            <div style="display: flex; gap: 30px;">
                <div>
                    <button id="etf-sheet-sync-button" type="button" class="button button-primary button-large"> Get ETF info from linked files </button>
                    <button id="etf-manual-edit-button" type="button" class="button button-primary button-large"> Edit </button>
                </div>
                <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>loadinganimation"> <img style="width:32px; height:32px;" src="<?php echo plugin_dir_url(__FILE__) . 'admin/images/Gear-0.2s-200px.gif'; ?>" alt="loading animation"> </div>
                <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: green; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-success"> Data Saved successfully </p>
                <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed"> Error Occured </p>
                <p class="<?php echo $this->prefix ?>status-states" style="display: none; color: red; font-weight: bold; margin: auto 0;" id="<?php echo $this->prefix ?>status-failed-url"> Enter Valid URL </p>
                <div class="<?php echo $this->prefix ?>status-states" style="display: none; margin: auto 0;" id="<?php echo $this->prefix ?>fetch-load"> </div>
            </div>
            </div>
<?php }
    }

    /**
     * Save the new Custom Fields values
     */
    function save_custom_fields($post_id, $post)
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'utils/keys.php';

        foreach (get_custom_feilds() as $customField) {
            if (current_user_can($customField['capability'], $post_id)) {
                if (isset($_POST[$this->prefix . $customField['name']]) && trim($_POST[$this->prefix . $customField['name']])) {
                    $value = $_POST[$this->prefix . $customField['name']];
                    if (
                        $customField['name'] === 'fund-footer-desc-data' ||
                        $customField['name'] === 'preformance-section-desc-data' ||
                        $customField['name'] === 'fund-header-textarea-data'
                    ) {
                        $value = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), "<br/>", $value);
                    }
                    update_post_meta($post_id, $this->prefix . $customField['name'], $value);
                }
            }
        }

        \ETFsFundDocs\FundDocuments::save_feilds($_POST, $post_id);

        (new Calculations())->init($post_id);
    }
} ?>