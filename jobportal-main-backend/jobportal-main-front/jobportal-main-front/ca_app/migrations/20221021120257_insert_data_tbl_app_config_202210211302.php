<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202210211302 extends CI_Migration
{
    public function up()
    {
        $row = $this->db->get_where('tbl_app_config', [
            'key' => 'email_notifications'
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => 'email_notifications',
                'value' => 'portalempleooverall@gmail.com'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'email_notifications');
        $this->db->delete('tbl_app_config');
    }
}
