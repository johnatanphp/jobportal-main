<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_contracts_202511060855 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'id'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
        $this->db->query("CREATE INDEX process_id ON tbl_recruitment_contracts (process_id)");
        $this->db->query("ALTER TABLE tbl_recruitment_contracts MODIFY COLUMN job_id int NULL");
    }

    public function down(){}
}
