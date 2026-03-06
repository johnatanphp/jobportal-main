<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_table_20120726 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'management' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '100',
            ],
            'division' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '100',
            ]
        ];
        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'management');
        $this->dbforge->drop_colum('tbl_staff_requests', 'division');
    }
}
