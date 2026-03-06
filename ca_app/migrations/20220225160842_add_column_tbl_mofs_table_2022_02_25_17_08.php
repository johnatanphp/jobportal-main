<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_mofs_table_2022_02_25_17_08 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'occupational_exams_approved' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_mofs', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_mofs', 'occupational_exams_approved');
    }
}
