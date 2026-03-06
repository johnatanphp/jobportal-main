<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202406041140 extends CI_Migration
{
    public function up()
    {
        $config = [
            ['ca_api_base_url', ''],
            ['ca_api_base_auth_user', ''],
            ['ca_api_base_auth_password', ''],
            ['ca_api_base_application_name', '']
        ];

        foreach ($config as $config_row) {

            $key = $config_row[0];
            $value = $config_row[1];

            $row = $this->db->get_where('tbl_app_config', [
                'key' => $key
            ])->row();
            
            if (!$row) {
                $this->db->insert('tbl_app_config', [
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }

        //Eliminar config 
        $this->db->where('key', 'ca_api_url_2');
        $this->db->delete('tbl_app_config');
    }

    public function down(){}
}
