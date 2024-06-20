<?php

class ConnectionServices {
    private $table_name = "etfs_sftp_connection";

    function sftp_db_init()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $wp_table_name (
                    id INT UNIQUE AUTO_INCREMENT,
                    Name varchar(255) NOT NULL DEFAULT '',
                    Automate char(1) NOT NULL DEFAULT 'f',
                    ActiveCycle char(1) NOT NULL DEFAULT 'f',
                    Host varchar(255) NOT NULL DEFAULT '',
                    User varchar(255) NOT NULL DEFAULT '',
                    Pass varchar(255) NOT NULL DEFAULT '',
                    Port varchar(255) NOT NULL DEFAULT '',
                    Timing varchar(255) NOT NULL DEFAULT '',
                    Nav varchar(255) NOT NULL DEFAULT '',
                    Holding varchar(255) NOT NULL DEFAULT '',
                    Ror varchar(255) NOT NULL DEFAULT '',
                    Ind varchar(255) NOT NULL DEFAULT '',
                    Sec varchar(255) NOT NULL DEFAULT '',
                    LastCycleTimestamp DATETIME
                ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function sftp_db_drop()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $sql = "DROP TABLE IF EXISTS $wp_table_name";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function create_sftp_connection($name)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $wpdb->insert(
            $wp_table_name,
            array(
                'Automate' => 'f',
                'ActiveCycle' => 'f',
                'Name' => $name,
                'Host' => '*',
                'User' => '*',
                'Pass' => '*',
                'Port' => '*',
                'Timing' => 'hourly',
                'Nav' => '*',
                'Holding' => '*',
                'Ror' => '*',
                'Ind' => '*',
                'Sec' => '*',
                'LastCycleTimestamp' => NULL
            )
        );
    }

    function sftp_remove_connection(int $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $wpdb->delete($wp_table_name, array('id' => $id));
    }

    function sftp_remove_remove_all()
    {
        $connections = $this->list_connections();
        for ($i = 0; $i < count($connections); $i++) {
            $connection = $connections[$i];
            $this->sftp_remove_connection((int) $connection["id"]);
        }
    }

    function get_config_db(int $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $config = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wp_table_name WHERE Id = %d", $id), ARRAY_A);
        return $config;
    }

    function list_connections()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $connections = $wpdb->get_results("SELECT * FROM $wp_table_name", ARRAY_A);
        return $connections;
    }

    
    function update_config_db(int $id, $args)
    {
        if (isset($args["automate"])) {
            $args["automate"] = $args["automate"] === true ? "t" : "f";
        }

        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $where = ['id' => $id]; // NULL value in WHERE clause.
        $wpdb->update($wp_table_name, $args, $where); // Also works in this case.

        return array('update' => 'success');
    }

    function cycle_timestamp(string $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;

        date_default_timezone_set("America/Chicago");
        $current_time = date("Y-m-d h:i:s A");

        $data = ['LastCycleTimestamp' => $current_time];

        $where = ['id' => $id];
        $wpdb->update($wp_table_name, $data, $where);
    }

    function force_turn_off(string $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $data = ['Automate' => 'f'];
        $where = ['id' => $id];
        $wpdb->update($wp_table_name, $data, $where);
    }

    function cycle_state($state, int $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $data = ['ActiveCycle' => $state];
        $where = ['id' => $id];
        $wpdb->update($wp_table_name, $data, $where);
    }
}
