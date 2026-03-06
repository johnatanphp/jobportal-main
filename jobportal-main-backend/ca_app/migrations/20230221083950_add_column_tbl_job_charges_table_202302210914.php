<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_charges_table_202302210914 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'skills' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'valorization_score' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'valorization_grade' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_job_charges', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_charges', 'valorization_score');
        $this->dbforge->drop_colum('tbl_job_charges', 'valorization_grade');
        $this->dbforge->drop_colum('tbl_job_charges', 'skills');
    }
}
