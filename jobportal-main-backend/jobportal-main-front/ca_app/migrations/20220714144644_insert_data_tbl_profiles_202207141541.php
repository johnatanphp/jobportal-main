<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_profiles_202207141541 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_profiles', [
            'id' => 6,
            'name' => 'CONTABILIDAD',
            'folder' => 'employer',
            'dashboard' => 'employer/recruitment_entry/entry_list_foreign',
            'session_key' => 'is_employer',
            'active' => 1

        ]);
    }

    public function down(){}
}
