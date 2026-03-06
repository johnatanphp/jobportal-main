<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_process_stages extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
            ],
            'stage_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (stage_id) REFERENCES tbl_recruitment_stages(id)');
        
        $this->dbforge->create_table('tbl_recruitment_process_stages');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_process_stages');
    }
}
