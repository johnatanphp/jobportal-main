<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_skills_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'skill_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ]
        ]);
      
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id)');
        $this->dbforge->create_table('tbl_job_layout_skills');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_skills');
    }
}
