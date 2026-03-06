<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_tbl_seeker_form_rtps_2025606301215 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'pension_join_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
                'after' => 'pension_join'
            ],
            'pension_join_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
                'null'       => true,
                'after' => 'pension_join_name'
            ],
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_seeker_form_rtps', 'pension_join_name');
        $this->dbforge->drop_column('tbl_seeker_form_rtps', 'pension_join_type');
    }
}
