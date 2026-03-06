<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_staff_request_resources_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'emo_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'emo_protocol_detail' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => false
            ],
            'emo_expense_type' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'emo_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'screening_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'screening_expense_type' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'screening_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'screening_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'covid19_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'covid19_expense_type' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'covid19_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'covid19_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'exam_complementary' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => false,
            ],
            'exam_complementary_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'verify_home' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'verify_home_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'verify_home_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'verify_credit' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'verify_credit_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'verify_credit_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'verify_labor' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'verify_labor_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'verify_labor_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],

            'verify_degree' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'verify_degree_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'verify_degree_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'verify_degree_person' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0
            ],
            'verify_degree_person_perform_stage' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'verify_degree_person_staff_charge' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'request_id' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (request_id) REFERENCES tbl_staff_requests(ID)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('emo_type_id');
        $this->dbforge->add_key('screening_type_id');
        $this->dbforge->add_key('covid19_type_id');
    
        $this->dbforge->create_table('tbl_staff_request_resources');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_staff_request_resources');
    }
}
