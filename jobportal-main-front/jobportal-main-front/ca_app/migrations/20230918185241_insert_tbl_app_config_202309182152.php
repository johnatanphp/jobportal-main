<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_app_config_202309182152 extends CI_Migration
{
    public function up()
    {
        $key = 'recruitment_seeker_importer_limit';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => '300'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'recruitment_seeker_importer_limit');
        $this->db->delete('tbl_app_config');
    }
}
