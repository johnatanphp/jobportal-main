<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_job_layouts_202405291040 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'code_integration' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'code',
                'unique' => true
            ],
        ];

        $this->dbforge->add_column('tbl_job_layouts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_job_layouts', 'code_integration');
    }
}
