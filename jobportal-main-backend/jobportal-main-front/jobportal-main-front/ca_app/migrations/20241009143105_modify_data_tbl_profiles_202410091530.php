<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_data_tbl_profiles_202410091530 extends CI_Migration
{
    public function up()
    {
        $this->db->where('id', 3);
        $this->db->update('tbl_profiles', [
            'name' => 'Gestor de nómina'
        ]);
    }

    public function down()
    {
        $this->db->where('id', 3);
        $this->db->update('tbl_profiles', [
            'name' => 'RRHH'
        ]);
    }
}
