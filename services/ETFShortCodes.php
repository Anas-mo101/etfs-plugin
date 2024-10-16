<?php


class ETFShortCodes
{
    function __construct()
    {
        add_shortcode('render-etf-page', array($this, 'render_product_page'));
        add_shortcode('render-top-holders-table', array($this, 'render_top_holders'));
        add_shortcode('render-subadvisor-section', array($this, 'render_subadvisor'));
        add_shortcode('render-toptenmobile', array($this, 'render_toptenmobile'));
        add_shortcode('render-nav-graph', array($this, 'render_graph'));
        add_shortcode('render-etf-content', array($this, 'render_content_cuz_elementor_dumb'));
        add_shortcode('render-post-category', array($this, 'get_post_category_cuz_elementor_cant'));
        add_shortcode('render-frontpage-box-content', array($this, 'render_frontpage_etfs'));
        add_shortcode('render-archive-category-elementor', array($this, 'get_post_category_in_archive_elementor'));
        add_shortcode('render-product-table-date', array($this, 'render_product_page_as_of_date'));
        add_shortcode('render-divz-charts-values', array($this, 'divs_charts_values'));
        add_shortcode('render-etf-fund-form', array($this, 'set_wpcf7_forms'));
    }

    // call shortcode [render-etf-page] to render product table
    function render_product_page()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/product_page.php');
        return ob_get_clean();
    }

    function render_product_page_as_of_date()
    {
        ob_start();
        $date = get_post_meta(custom_get_page_by_title('JANZ', OBJECT, 'etfs')->ID, 'ETF-Pre-rate-date-data', true);
        echo $date;
        return ob_get_clean();
    }

    function render_top_holders()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/topten.php');
        return ob_get_clean();
    }

    function render_subadvisor()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/subadvisors.php');
        return ob_get_clean();
    }

    function render_toptenmobile()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/topten_mobile.php');
        return ob_get_clean();
    }

    function render_graph()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/nav_graph.php');
        return ob_get_clean();
    }

    function divs_charts_values()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/divz_charts.php');
        return ob_get_clean();
    }

    function render_content_cuz_elementor_dumb()
    {
        ob_start();
        global $post;
        echo $post->post_content;
        return ob_get_clean();
    }

    function get_post_category_cuz_elementor_cant()
    {
        ob_start();
        if ('post' === get_post_type()) {
            global $post;
            $cat_names = array();
            $category_detail = get_the_category($post->ID ?? false);
            foreach ($category_detail as $cd) {
                $cat_names[] = $cd->cat_name;
            }
            echo implode(", ", $cat_names);
        }
        return ob_get_clean();
    }

    function get_post_category_in_archive_elementor()
    {
        ob_start();
        global $post;
        $category_detail = get_the_category($post->ID ?? false);
        $cate = is_archive() ? (isset($category_detail[0])  ? $category_detail[0]->cat_name : 'Uncategorized') : 'All';
        ?> <style>
            span.elementor-post-date::before {
                content: "<?php echo htmlspecialchars_decode($cate) ?> - " !important;
            }
        </style> <?php
        return ob_get_clean();
    }

    function set_wpcf7_forms()
    {
        ob_start();
        global $post;
        $title = $post->post_title;
        $form = custom_get_page_by_title($title . ' Fund', OBJECT, 'wpcf7_contact_form');
        if ($form) {
            echo do_shortcode('[contact-form-7 id="' . $form->ID . '" title="' . $title . ' Fund"]');
        }
        return ob_get_clean();
    }

    function render_frontpage_etfs()
    {
        ob_start();
        include(WP_PLUGIN_DIR . '/etfs/view/shortcodes/boxes.php');
        return ob_get_clean();
    }
}
