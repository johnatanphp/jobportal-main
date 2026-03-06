<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_recruitment_tray_candidates_202508151140 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'company_id'
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_candidates', $fields); 
        $this->db->query('ALTER TABLE tbl_recruitment_tray_candidates ADD CONSTRAINT FOREIGN KEY (process_id) REFERENCES tbl_recruitment_process(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->dbforge->drop_column('tbl_recruitment_tray_candidates', 'job_id');
    }

    public function down(){}
}
