<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_202412031640 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'pension_join_system_pension' => [
                'type' => 'TINYINT',
                'null' => true,
                'after' => 'pension_affiliation_date'
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_seeker_form_rtps', 'pension_join_system_pension');
    }
}
