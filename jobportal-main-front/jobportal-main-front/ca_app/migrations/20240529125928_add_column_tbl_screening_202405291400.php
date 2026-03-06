<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202405291400 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => false,
                'default' => '',
                'after' => 'document_number'
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => false,
                'default' => '',
                'after' => 'first_name'
            ],
        ];

        $this->dbforge->add_column('tbl_screening', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'first_name');
        $this->dbforge->drop_colum('tbl_screening', 'last_name');
    }
}
