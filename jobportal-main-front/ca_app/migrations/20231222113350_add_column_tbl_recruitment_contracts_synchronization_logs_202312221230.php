<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_synchronization_logs_202312221230 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],    
            'ref_id' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts_synchronization_logs', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts_synchronization_logs', 'description');
        $this->dbforge->drop_colum('tbl_recruitment_contracts_synchronization_logs', 'ref_id');
    }
}
