<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layouts_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => true
            ],
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
                'null' => false
            ],
            'risk_criteria' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => true
            ],
            'job_charge_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'education' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'study_grade_req' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'education_req_detail' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'study_grade_min' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'education_min_detail' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'experience' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false
            ],
            'experience_detail' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'created_by_recruiter_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => false,
                'default' => 1
            ],
            'requested' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => false
            ],
            'sunat_code' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => true
            ],
            'basic_minimum' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true
            ],
            'basic_maximum' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true
            ],
            'stereotype' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'factor_differentiating' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'factor_differentiating_other' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'occupational_category_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'occupational_exams_approved' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => false,
                'default' => 0
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'last_update' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (job_charge_id) REFERENCES tbl_job_charges(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (created_by_recruiter_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (occupational_category_id) REFERENCES tbl_occupational_categories(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (study_grade_req) REFERENCES tbl_qualifications(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (study_grade_min) REFERENCES tbl_qualifications(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('code');
        
        $this->dbforge->create_table('tbl_job_layouts');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layouts');
    }
}
