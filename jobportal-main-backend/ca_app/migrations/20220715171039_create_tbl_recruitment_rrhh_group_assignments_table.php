<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_rrhh_group_assignments_table extends CI_Migration
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
                'unsigned' => TRUE,
                'null' => false
            ],
            'rrhh_group_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (rrhh_group_id) REFERENCES tbl_recruitment_rrhh_groups(id)');

        $this->dbforge->create_table('tbl_recruitment_rrhh_group_assignments');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_rrhh_group_assignments');
    }
}
