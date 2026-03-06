<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_profile_resources_table_2022_02_14_1906 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'type_expense' => [
                'type' => 'varchar',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'resource_value'
            ]
        ];
    
        $this->dbforge->add_column('tbl_job_profile_resources', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_profile_resources', 'type_expense');
    }    
}
