<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_responsibilities_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'responsibility' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ]
        ]);
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id)');
        $this->dbforge->create_table('tbl_job_layout_responsibilities');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_responsibilities');
    }
}
