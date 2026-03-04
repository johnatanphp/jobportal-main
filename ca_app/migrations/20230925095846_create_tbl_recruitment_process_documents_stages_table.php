<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_process_documents_stages_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'job_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'stage_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'document_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ]
        ]);

        $this->dbforge->add_key(['job_id', 'stage_id', 'document_id', ], TRUE);
        $this->dbforge->create_table('tbl_recruitment_process_documents_stages');

        $this->db->query('ALTER TABLE tbl_recruitment_process_documents_stages ADD CONSTRAINT FOREIGN KEY (document_id) REFERENCES tbl_recruitment_document_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_process_documents_stages');
    }
}
