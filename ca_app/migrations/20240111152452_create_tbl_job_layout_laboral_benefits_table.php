<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_laboral_benefits_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'benefit_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
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

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (benefit_id) REFERENCES tbl_laboral_benefits(ID)');    
        $this->dbforge->add_key('id', TRUE);
    
        $this->dbforge->create_table('tbl_job_layout_laboral_benefits');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_laboral_benefits');
    }
}
