<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_profiles_202312071847 extends CI_Migration
{
    public function up()
    {

        $profile = $this->db->get_where('tbl_profiles', [
            'id' => 7
        ])->row();
        
        if (!$profile) {

            $this->db->insert('tbl_profiles', [
                'id' => 7,
                'name' => 'Gestor de programaciones',
                'folder' => 'employer',
                'dashboard' => 'employer/recruitment_scheduled_exams/scheduled_exams',
                'session_key' => 'is_employer',
                'active' => 1
            ]);
        }
    
    }

    public function down(){}
}
