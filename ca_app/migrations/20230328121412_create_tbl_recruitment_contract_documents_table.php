<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contract_documents_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
            ],
            'file_path' => [
                'type' => 'TEXT',
                'default' => '',
                'null' => false
            ],
            'job_id' => [
                'type' => 'int',
                'unsigned' => false,
                'null' => true
            ],
            'seeker_id' => [
                'type' => 'int',
                'unsigned' => false,
            ],
            'document_id' => [
                'type' => 'int',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'default' => null
            ],            
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('seeker_id');

        $this->dbforge->create_table('tbl_recruitment_contract_documents');

        $this->db->query('ALTER TABLE tbl_recruitment_contract_documents ADD CONSTRAINT FOREIGN KEY (document_id) REFERENCES tbl_recruitment_contract_document_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contract_documents');
    }
}
