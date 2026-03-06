<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202405141700 extends CI_Migration
{
    public function up()
    {
        $config = [
            ['sap_api_url', ''],
            ['sap_api_db', ''],
            ['sap_api_username', ''],
            ['sap_api_password', ''],
            ['sap_api_enabled', '0']
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
    }

    public function down()
    {
        $this->db->where_in('key', [
            'sap_api_url',
            'sap_api_db',
            'sap_api_username',
            'sap_api_password',
            'sap_api_enabled'
        ]);
        $this->db->delete('tbl_app_config');
    }
}
