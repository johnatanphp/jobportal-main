<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_profile_laboral_benefits_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_profile_id' => [
                'type' => 'INT',
                'unsigned' => false
            ],
            'benefit_id' => [
                'type' => 'INT',
                'unsigned' => false
            ],
            'minimum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'maximum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_profile_id');
        $this->dbforge->add_key('benefit_id');

        $this->dbforge->create_table('tbl_job_profile_laboral_benefits');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_profile_laboral_benefits');
    }
}
