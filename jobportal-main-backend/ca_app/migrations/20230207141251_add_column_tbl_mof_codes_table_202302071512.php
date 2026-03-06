<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_mof_codes_table_202302071512 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_mof_codes DROP INDEX acronym");
        $this->db->query("ALTER TABLE tbl_mof_codes DROP INDEX job_title");

        $fields = [
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_mof_codes', $fields);

        $this->db->query("CREATE INDEX acronym ON tbl_mof_codes (acronym)");
        $this->db->query("CREATE INDEX job_title ON tbl_mof_codes (job_title)");   
        $this->db->query("CREATE INDEX company_id ON tbl_mof_codes (company_id)");   
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_mof_codes', 'company_id');
    }
}
