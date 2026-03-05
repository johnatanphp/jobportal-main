<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_entry_form_mx_202501021630 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'second_name' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => false,
                'after' => 'first_name'
            ],
            'third_name' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => false,
                'after' => 'second_name'
            ]
        ];

        $this->dbforge->add_column('tbl_entry_form_mx', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_entry_form_mx', 'second_name');
        $this->dbforge->drop_colum('tbl_entry_form_mx', 'third_name');
    }       
}
