<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_candidates_table_2022_03_04_1715 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'ignore_form_answers' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_candidates', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_candidates', 'ignore_form_answers');
    }
}
