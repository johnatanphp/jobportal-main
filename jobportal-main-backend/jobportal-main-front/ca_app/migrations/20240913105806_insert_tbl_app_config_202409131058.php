<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_app_config_202409131058 extends CI_Migration
{
    public function up()
    {
        $config = [
            ['hrm_api2_bearer_token', '1'],
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

    public function down(){}
}
