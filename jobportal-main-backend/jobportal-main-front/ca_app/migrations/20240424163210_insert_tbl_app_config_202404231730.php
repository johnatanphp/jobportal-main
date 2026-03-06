<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_app_config_202404231730 extends CI_Migration
{
    public function up()
    {
        $key = 'screening_permissions_users';

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
        $this->db->where('key', 'screening_permissions_users');
        $this->db->delete('tbl_app_config');
    }
}
