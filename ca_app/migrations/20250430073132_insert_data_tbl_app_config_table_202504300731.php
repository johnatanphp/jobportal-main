<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_app_config_table_202504300731 extends CI_Migration
{
    public function up()
    {
        $configs = [
            'hrmgo_api_url'      => '',
            'hrmgo_api_url_email'=> '',
            'hrmgo_api_url_pswd' => ''
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

    public function down()
    {
        $keys = [
            'hrmgo_api_url',
            'hrmgo_api_url_email',
            'hrmgo_api_url_pswd'
        ];

        $this->db->where_in('key', $keys)->delete('tbl_app_config');
    }
}
