<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202309062050 extends CI_Migration
{
    public function up()
    {
        $key = 'hrm_api2_url';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => 'https://hrmdesa.overall.pe/hrm_api/public/api'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'hrm_api2_url');
        $this->db->delete('tbl_app_config');
    }
}
