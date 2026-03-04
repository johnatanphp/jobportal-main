<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202406131655 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'eecc_job_vacancies' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'eecc_form_id',
            ],
            'eecc_job_code' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false,
                'default' => '',
                'after' => 'eecc_job_vacancies',
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'eecc_job_vacancies');
        $this->dbforge->drop_colum('tbl_staff_requests', 'eecc_job_code');
    }
}
