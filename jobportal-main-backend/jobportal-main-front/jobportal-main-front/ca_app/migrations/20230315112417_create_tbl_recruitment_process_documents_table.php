<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_process_documents_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'document_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->create_table('tbl_recruitment_process_documents');

        $this->db->query('ALTER TABLE tbl_recruitment_process_documents ADD CONSTRAINT FOREIGN KEY (document_id) REFERENCES tbl_recruitment_document_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_process_documents');
    }
}
