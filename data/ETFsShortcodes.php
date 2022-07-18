<?php
namespace ETfsSC;

class ETFsShortcodes{

    var $shortcodes_data = array(
        'render-etf-page' => 'etf-product-page.php',
        'render-frontpage-box-content' => 'frontpage-etfs-boxes.php',
        'render-nav-graph' => 'nav-graph.php',
        'render-subadvisor-section' => 'subadvisorshortcode.php',
        'render-toptenmobile' => 'toptenmobile.php',
        'render-top-holders-table' => 'toptenshortcode.php',
    );

    function __construct(){
        $this->reg_shortcodes();
        // ========== others ============
        add_shortcode('render-etf-content', array($this, 'render_content_cuz_elementor_dumb'));
    }

    function reg_shortcodes(){
        foreach ($this->shortcodes_data as $code => $file_name) {
            add_shortcode($code, function() use ($file_name) {
                ob_start();
                include( WP_PLUGIN_DIR . `/etfs-plugin/shortcodes/$file_name`);
                return ob_get_clean();
            });
        }
    } 
    //plugin_dir_url(dirname(__FILE__)) .`/etfs-plugin/shortcodes/$file_name`

    function render_content_cuz_elementor_dumb() {
        ob_start();
        global $post;
        echo $post->post_content; 
        return ob_get_clean();
    }
}