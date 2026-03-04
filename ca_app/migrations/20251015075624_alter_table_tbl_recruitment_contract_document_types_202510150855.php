<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_contract_document_types_202510150855 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'group_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_contract_document_types', $fields); 
        $this->db->query('ALTER TABLE tbl_recruitment_contract_document_types ADD CONSTRAINT FOREIGN KEY (group_id) REFERENCES tbl_contract_document_groups(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down(){}
}
