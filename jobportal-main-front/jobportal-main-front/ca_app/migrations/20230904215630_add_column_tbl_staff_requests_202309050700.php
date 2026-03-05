<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202309050700 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'user_management' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '100'
            ],
            'applicant_headquarter' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '150'
            ],
            'type_contracting' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '20'
            ],
            'salary_delivery_period' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '80'
            ],
            'renovable' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => '20',
            ],
            'start_date_work' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'observations' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'belonging_area_ID' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => false,
            ],
            'employee_change_file_path' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'n_people_reporting' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true
            ],
            'modality_contracting' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'job_mode' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => TRUE,
            ],
            'start_hour_lunch' => [
                'type' => 'TIME',
                'null' => TRUE,
            ],
            'end_hour_lunch' => [
                'type' => 'TIME',
                'null' => TRUE
            ],
            'workplace' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE,
            ],
            'household_allowance' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'labor_experience_time' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
            ],
            'minimum_age' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
            'maximum_age' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
            'general_knowledges' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'specific_knowledges' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'additional_comments' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ]
        ];
      
        $this->dbforge->add_column('tbl_staff_requests', $fields); 

        $this->db->query("CREATE INDEX belonging_area_ID ON tbl_staff_requests (belonging_area_ID)");   
    }

    public function down(){}
}
