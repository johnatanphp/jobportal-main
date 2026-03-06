<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_seeker_documents_table_202303311331 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'document_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'document_key'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_seeker_documents', $fields);  
        $this->db->query('ALTER TABLE tbl_recruitment_seeker_documents ADD CONSTRAINT FOREIGN KEY (document_id) REFERENCES tbl_recruitment_contract_document_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION'); 
    }
    
    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_seeker_documents', 'document_id');
    }
}
