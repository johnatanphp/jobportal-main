<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_202509021050 extends CI_Migration
{
    public function up()
    {
        $configs = [
            'aws_lambda_sqs_key' => 'key',
            'aws_lambda_sqs_secret' => 'secret',
        ];

        foreach ($configs as $key => $value) {
            $exists = $this->db->get_where('tbl_app_config', ['key' => $key])->row();
            if (!$exists) {
                $this->db->insert('tbl_app_config', [
                    'key'   => $key,
                    'value' => $value
                ]);
            }
        }
    }

    public function down(){}
}
