<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202402011535 extends CI_Migration
{
    public function up()
    {
        $key = 'job_layouts_module_enabled';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => '0'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'job_layouts_module_enabled');
        $this->db->delete('tbl_app_config');
    }
}
