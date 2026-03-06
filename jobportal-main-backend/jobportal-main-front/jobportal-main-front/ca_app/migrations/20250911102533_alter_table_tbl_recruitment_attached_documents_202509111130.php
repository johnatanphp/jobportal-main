<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_attached_documents_202509111130 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'job_ID'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_attached_documents', $fields); 
        $this->db->query("CREATE INDEX process_id ON tbl_recruitment_attached_documents (process_id)");
        $this->db->query("ALTER TABLE tbl_recruitment_attached_documents MODIFY COLUMN job_ID int unsigned NULL");
    }

    public function down(){}
}
