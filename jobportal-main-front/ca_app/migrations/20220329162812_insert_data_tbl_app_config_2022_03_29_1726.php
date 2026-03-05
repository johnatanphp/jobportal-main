<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_2022_03_29_1726 extends CI_Migration
{
    public function up()
    {
        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'hrm_api_key'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'hrm_api_key',
                'value' => ''
            ]);
        }
        
        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'hrm_api_url'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'hrm_api_url',
                'value' => ''
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'hrm_api_key');
        $this->db->delete('tbl_app_config');
    }
}
