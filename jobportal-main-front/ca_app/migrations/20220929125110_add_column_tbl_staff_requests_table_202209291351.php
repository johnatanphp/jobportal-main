<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_table_202209291351 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'eecc_code' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '20',
                'default' => '',
            ],
            'eecc_description' => [
                'type' => 'TEXT',
                'null' => false,
                'default' => '',
            ]
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'eecc_code');
        $this->dbforge->drop_colum('tbl_staff_requests', 'eecc_description');
        
    }
}
