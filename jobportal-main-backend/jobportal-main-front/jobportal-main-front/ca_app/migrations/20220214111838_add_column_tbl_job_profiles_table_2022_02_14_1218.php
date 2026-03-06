<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_profiles_table_2022_02_14_1218 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'sunat_code' => [
                'type' => 'varchar',
                'constraint' => '180',
                'null' => TRUE
            ],
            'basic_minimum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'basic_maximum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'food_minimum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'food_maximum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'mobility_minimum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'mobility_maximum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'bonuses_commissions_minimum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'bonuses_commissions_maximum' => [
                'type' => 'decimal',
                'constraint' => '15,2',
                'null' => TRUE
            ],
            'stereotype' => [
                'type' => 'varchar',
                'constraint' => '100',
                'null' => TRUE
            ],
            'factor_differentiating' => [
                'type' => 'varchar',
                'constraint' => '100',
                'null' => TRUE
            ],
            'factor_differentiating_other' => [
                'type' => 'text',
                'null' => TRUE
            ],
            'occupational_category_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE
            ],
        ];
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (occupational_category_id) REFERENCES tbl_occupational_categories(id)');
        $this->dbforge->add_column('tbl_job_profiles', $fields);
        $this->dbforge->add_key('occupational_category_id');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_profiles', 'sunat_code');
        $this->dbforge->drop_colum('tbl_job_profiles', 'basic_minimum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'basic_maximum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'food_minimum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'food_maximum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'mobility_minimum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'mobility_maximum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'bonuses_commissions_minimum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'bonuses_commissions_maximum');
        $this->dbforge->drop_colum('tbl_job_profiles', 'stereotype');
        $this->dbforge->drop_colum('tbl_job_profiles', 'factor_differentiating');
        $this->dbforge->drop_colum('tbl_job_profiles', 'factor_differentiating_other');
        $this->dbforge->drop_colum('tbl_job_profiles', 'occupational_category_id');
    }   
}
