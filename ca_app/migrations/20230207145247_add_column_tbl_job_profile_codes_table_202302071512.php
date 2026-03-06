<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_profile_codes_table_202302071512 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_job_profile_codes DROP INDEX acronym");

        $fields = [
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_job_profile_codes', $fields);

        $this->db->query("CREATE INDEX acronym ON tbl_job_profile_codes (acronym)");   
        $this->db->query("CREATE INDEX company_id ON tbl_job_profile_codes (company_id)");   
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_profile_codes', 'company_id');
    }
}
