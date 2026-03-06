<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_app_config_table_202305231013 extends CI_Migration
{
    public function up()
    {
        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'eplani_api_key'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'eplani_api_key',
                'value' => ''
            ]);
        }

        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'eplani_api_enabled'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'eplani_api_enabled',
                'value' => '1'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'eplani_api_key');
        $this->db->delete('tbl_app_config');

        $this->db->where('key', 'eplani_api_enabled');
        $this->db->delete('tbl_app_config');
    }
}
