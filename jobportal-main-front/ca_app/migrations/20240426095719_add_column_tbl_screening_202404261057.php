<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202404261057 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'type_expense' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ]
        ];

        $this->dbforge->add_column('tbl_screening', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'type_expense');
    }
}
