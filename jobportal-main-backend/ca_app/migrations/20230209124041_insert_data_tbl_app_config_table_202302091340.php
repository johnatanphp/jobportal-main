<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_table_202302091340 extends CI_Migration
{
    public function up()
    {
        $key = 'workflow_migration_company_ids';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => ''
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'workflow_migration_company_ids');
        $this->db->delete('tbl_app_config');
    }
}
