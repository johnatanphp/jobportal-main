<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_domicile_affidavits_table_202205181952 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'data_log' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'evicertia_error'
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_domicile_affidavits', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_domicile_affidavits', 'data_log');
    }
}
