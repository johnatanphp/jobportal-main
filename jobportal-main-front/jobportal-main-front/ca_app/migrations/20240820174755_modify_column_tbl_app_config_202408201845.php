<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_column_tbl_app_config_202408201845 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_app_config MODIFY COLUMN value TEXT NOT NULL;');
    }

    public function down(){}
}
