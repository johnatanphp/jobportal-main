<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_insert_data_tbl_app_config_202405201230 extends CI_Migration
{
    public function up()
    {
        $config = [
            ['system_payroll', 'eplani'],
            ['system_accounting', 'ebs'],
            ['system_internal', 'integrado']
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
            'system_payroll',
            'system_accounting',
            'system_internal'
        ]);
        $this->db->delete('tbl_app_config');
    }
}
