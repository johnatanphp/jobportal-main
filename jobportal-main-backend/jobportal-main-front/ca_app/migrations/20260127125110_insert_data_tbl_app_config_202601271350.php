<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202601271350 extends CI_Migration
{
    public function up()
    {
        $config = [
            ['screening_api_auth_1_username', ''],
            ['screening_api_auth_1_password', ''],
            ['screening_api_auth_2_username', ''],
            ['screening_api_auth_2_password', ''],
            ['screening_api_auth_3_username', ''],
            ['screening_api_auth_3_password', '']
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
    
    }
}
