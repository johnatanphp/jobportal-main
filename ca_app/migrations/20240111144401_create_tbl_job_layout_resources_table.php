<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_resources_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'resource' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'resource_value' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'type_expense' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'staff_in_charge' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => false
            ],
            'job_layout_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'perform_on_stage' => [
                'type' => 'INT',
                'null' => true
            ],
            'protocol_detail' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_layout_id) REFERENCES tbl_job_layouts(id)');
        $this->dbforge->create_table('tbl_job_layout_resources');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_resources');
    }
}
