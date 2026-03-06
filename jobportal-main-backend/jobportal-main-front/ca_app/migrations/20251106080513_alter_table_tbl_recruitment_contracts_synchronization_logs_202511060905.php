<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_contracts_synchronization_logs_202511060905 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'created_at'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts_synchronization_logs', $fields); 
        $this->db->query("CREATE INDEX process_id ON tbl_recruitment_contracts_synchronization_logs (process_id)");
        $this->db->query("ALTER TABLE tbl_recruitment_contracts_synchronization_logs MODIFY COLUMN job_id int NULL");
    }

    public function down(){}
}
