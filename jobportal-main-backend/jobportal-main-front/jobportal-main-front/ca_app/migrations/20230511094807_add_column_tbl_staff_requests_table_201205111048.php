<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_table_201205111048 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'zone' => [
                'type' => 'TEXT',
                'null' => false,
                'default' => ''
            ],
            'zone_comments' => [
                'type' => 'TEXT',
                'null' => false
            ]
        ];
        $this->dbforge->add_column('tbl_staff_requests', $fields);  
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'department');
        $this->dbforge->drop_colum('tbl_staff_requests', 'zone');
        $this->dbforge->drop_colum('tbl_staff_requests', 'zone_comments');
    }
}
