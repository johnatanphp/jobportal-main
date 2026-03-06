<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_table_202405211240 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'eecc_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false,
                'default' => ''
            ],
            'cost_center_client' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => false,
                'default' => ''
            ],
        ];

        $this->dbforge->add_column('tbl_screening', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'eecc_code');
        $this->dbforge->drop_colum('tbl_screening', 'cost_center_client');
        
    }
}
