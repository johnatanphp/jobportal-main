<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_profiles_table_202301281226 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_job_profiles', $fields);

        $this->db->query("CREATE INDEX company_id ON tbl_job_profiles (company_id)"); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_profiles', 'company_id');
    }
}
