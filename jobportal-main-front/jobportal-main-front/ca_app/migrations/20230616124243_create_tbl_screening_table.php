<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_screening_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'seeker_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => TRUE,
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => TRUE,
            ],
            'document_type' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => TRUE,
            ],
            'document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false,
                'default' => ''
            ],
            'type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'parameters' => [
                'type' => 'TEXT',
                'null' => false,
                'default' => ''
            ],
            'response_code' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
                'default' => 0
            ],
            'response' => [
                'type' => 'LONGTEXT',
                'null' => true
            ],
            'no_cia' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'business_unit_code' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'cost_center' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('document_number');
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (type_id) REFERENCES tbl_screening_types(id)');
        $this->dbforge->create_table('tbl_screening');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_screening');
    }
}
