<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_profiles_table_2022_02_25_17_11 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'occupational_exams_approved' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_job_profiles', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_profiles', 'occupational_exams_approved');
    }
}
