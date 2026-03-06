<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_profiles_table_2022_03_02_1828 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_profiles', [
            'id' => 5,
            'name' => 'Legal',
            'folder' => 'employer',
            'dashboard' => 'employer/recruitment_entry/entry_list_foreign',
            'session_key' => 'is_employer',
            'active' => 1
        ]);
    }

    public function down()
    {
        $this->db->where('id', 5);
        $this->db->delete('tbl_profiles');
    }
}
