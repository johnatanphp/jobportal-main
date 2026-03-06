<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_20231171450 extends CI_Migration
{
    public function up()
    {
        $key = 'staff_request_assignment_employers_limit';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => '4'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'staff_request_assignment_employers_limit');
        $this->db->delete('tbl_app_config');
    }
}
