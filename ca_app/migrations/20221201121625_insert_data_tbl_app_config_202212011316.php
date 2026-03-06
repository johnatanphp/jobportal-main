<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202212011316 extends CI_Migration
{
    public function up()
    {
        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'hrm_url'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'hrm_url',
                'value' => 'https://hrm.overall.pe/hrm/public/index.php'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'hrm_url');
        $this->db->delete('tbl_app_config');
    }
}
