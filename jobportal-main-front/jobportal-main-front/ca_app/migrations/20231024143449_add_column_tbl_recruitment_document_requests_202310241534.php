<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_document_requests_202310241534 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'job_id' => [
                'type' => 'INT',
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_document_requests', $fields); 
        $this->db->query("CREATE INDEX job_id ON tbl_recruitment_document_requests (job_id)");
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_document_requests', 'job_id');
    }
}
