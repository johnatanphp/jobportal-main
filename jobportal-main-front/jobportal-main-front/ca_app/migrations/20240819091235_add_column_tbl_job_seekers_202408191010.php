<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_seekers_202408191010 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'department_id' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => false,
                'after' => 'country'
            ],
            'province_id' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => false,
                'after' => 'department_id'
            ],
            'district_id' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => false,
                'after' => 'province_id'
            ]
        ];

        $this->dbforge->add_column('tbl_job_seekers', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_seekers', 'department_id'); 
        $this->dbforge->drop_colum('tbl_job_seekers', 'province_id'); 
        $this->dbforge->drop_colum('tbl_job_seekers', 'district_id');        
    }
}
