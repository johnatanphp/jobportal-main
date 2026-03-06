<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_candidates_202407311840 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_candidates', $fields); 
        $this->db->query('ALTER TABLE tbl_recruitment_candidates ADD CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_recruitment_candidates ADD CONSTRAINT FOREIGN KEY (process_id) REFERENCES tbl_staff_request_job_processes(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_candidates', 'process_id');
        $this->dbforge->drop_colum('tbl_recruitment_candidates', 'created_by');
    }
}
