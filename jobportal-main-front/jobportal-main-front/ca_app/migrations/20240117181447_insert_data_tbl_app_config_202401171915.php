<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202401171915 extends CI_Migration
{
    public function up()
    {
        $key = 'ca_api_url_2';

        $row = $this->db->get_where('tbl_app_config', [
            'key' => $key
        ])->row();
        
        if (!$row) {
            $this->db->insert('tbl_app_config', [
                'key' => $key,
                'value' => 'https://app.casistemas.com'
            ]);
        }
    }

    public function down()
    {
        $this->db->where('key', 'ca_api_url_2');
        $this->db->delete('tbl_app_config');
    }
}
