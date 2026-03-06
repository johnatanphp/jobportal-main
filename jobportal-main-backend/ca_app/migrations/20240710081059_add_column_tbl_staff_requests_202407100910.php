<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202407100910 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'type_remuneration' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true,
                'after' => 'monthly_gross_salary'
            ],
            'cost_center_client' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => false,
                'default' => '',
                'after' => 'additional_comments'
            ]
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'cost_center_client');
        $this->dbforge->drop_colum('tbl_staff_requests', 'type_remuneration');
    }
}
