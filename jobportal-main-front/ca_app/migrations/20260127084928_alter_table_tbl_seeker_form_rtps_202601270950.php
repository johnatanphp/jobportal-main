<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_seeker_form_rtps_202601270950 extends CI_Migration
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

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'second_name');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'third_name');
    }
}
