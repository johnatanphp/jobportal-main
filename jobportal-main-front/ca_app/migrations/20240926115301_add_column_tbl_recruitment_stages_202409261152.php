<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_stages_202409261152 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'its_screening' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_stages', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_recruitment_stages', 'its_screening');
    }
}
