<?php

class DynamicProductsTable
{
    private $table_name = "etfs_sftp_dynamic_table";

    function init()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $wp_table_name (
                    id INT UNIQUE AUTO_INCREMENT,
                    Name varchar(255) NOT NULL,
                    FileName varchar(255) NOT NULL,
                    ConnectionId INT NOT NULL,
                    Torder INT NOT NULL,
                    TableData LONGTEXT
                ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function update_tables_by_file_name($connectionId, $fileName, $tableData) {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $data = ['TableData' => $tableData];
        $where = ['FileName' => $fileName, "ConnectionId" => $connectionId];
        $wpdb->update($wp_table_name, $data, $where);
    }

    function dynamic_db_drop()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $sql = "DROP TABLE IF EXISTS $wp_table_name";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function create_dynamice_table($name, $connection, $fileName, $order, $data)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $wpdb->insert(
            $wp_table_name,
            array(
                'Name' => $name,
                'ConnectionId' => $connection,
                'FileName' => $fileName,
                'Torder' => $order,
                'TableData' => $data
            )
        );
    }

    function remove_dynamice_table(int $id)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $wpdb->delete($wp_table_name, array('id' => $id));
    }

    function list_tables()
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $tables = $wpdb->get_results("SELECT * FROM $wp_table_name ORDER BY Torder ASC", ARRAY_A);
        return $tables;
    }

    function update_table(int $id, $args)
    {
        global $wpdb;
        $wp_table_name = $wpdb->prefix . $this->table_name;
        $where = ['id' => $id];
        $wpdb->update($wp_table_name, $args, $where); 
        return array('update' => 'success');
    }
}

?>