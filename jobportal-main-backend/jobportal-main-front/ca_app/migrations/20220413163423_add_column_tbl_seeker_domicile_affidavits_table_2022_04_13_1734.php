<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_domicile_affidavits_table_2022_04_13_1734 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'file_path'
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_domicile_affidavits', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_domicile_affidavits', 'address');
    }
}
