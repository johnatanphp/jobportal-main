<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_app_config_202310101347_table extends CI_Migration
{
    public function up()
    {
        $config_list = [
            'ca_api_enabled' => '1',
            'ca_api_url' => '',
            'ca_api_auth_user' => '',
            'ca_api_auth_password' => '',
            'ca_api_database' => '',
            'ca_api_cod_user' => ''
        ];

        foreach ($config_list as $config_key => $config_value) {
            $row = $this->db->get_where('tbl_app_config', [
                'key' => $config_key
            ])->row();
            
            if (!$row) {
                $this->db->insert('tbl_app_config', [
                    'key' => $config_key,
                    'value' => $config_value
                ]);
            }
        }   
    }

    public function down()
    {
        $this->db->where_in('key', [
            'ca_api_enabled',
            'ca_api_url',
            'ca_api_auth_user',
            'ca_api_auth_password',
            'ca_api_database',
            'ca_api_cod_user'
        ]);
        $this->db->delete('tbl_app_config');
    }
}
