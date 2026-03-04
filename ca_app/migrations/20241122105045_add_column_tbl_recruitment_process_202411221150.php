<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_process_202411221150 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'job_ID'
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => TRUE,
                'after' => 'created_at'
            ],
            'expiration_date' => [
                'type' => 'DATE',
                'null' => TRUE,
                'after' => 'created_by'
            ],
            'expired' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0,
                'after' => 'expiration_date'
            ],
            'resumed' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0,
                'after' => 'expired'
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_process', $fields);
        $this->db->query('ALTER TABLE tbl_recruitment_process ADD CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(id) ON DELETE NO ACTION ON UPDATE NO ACTION'); 
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_process', 'created_at');
        $this->dbforge->drop_column('tbl_recruitment_process', 'created_by');
        $this->dbforge->drop_column('tbl_recruitment_process', 'expiration_date');  
        $this->dbforge->drop_column('tbl_recruitment_process', 'expired');  
        $this->dbforge->drop_column('tbl_recruitment_process', 'resumed');      
    }
}
