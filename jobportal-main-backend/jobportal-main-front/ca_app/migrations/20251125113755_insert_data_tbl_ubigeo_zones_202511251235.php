<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_ubigeo_zones_202511251235 extends CI_Migration
{
    public function up()
    {
        $zones = [
            'Norte Chico',
            'Sur Chico',
            'Lima Moderna'
        ];

        foreach ($zones as $zone) {
            $this->db->insert('tbl_ubigeo_zones', [
                'name' => $zone,
                'active' => 1
            ]);
        }
    }

    public function down(){}
}
