<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_202409301550 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false,
                'after' => 'no_cia'
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
        $this->db->query('ALTER TABLE tbl_recruitment_contracts ADD CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id) ON DELETE NO ACTION ON UPDATE NO ACTION'); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'job_layout_id');
    }
}
