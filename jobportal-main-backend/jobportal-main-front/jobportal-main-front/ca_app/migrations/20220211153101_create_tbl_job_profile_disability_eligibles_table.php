<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_profile_disability_eligibles_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'disability' => [
                'type' => 'TEXT',
            ],
            'resources' => [
                'type' => 'TEXT',
            ],
            'job_profile_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_profile_id');
        $this->dbforge->create_table('tbl_job_profile_disability_eligibles');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_profile_disability_eligibles');
    }
}
