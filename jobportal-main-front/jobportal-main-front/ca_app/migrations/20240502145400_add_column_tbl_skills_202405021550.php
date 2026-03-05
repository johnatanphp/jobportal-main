<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_skills_202405021550 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ];

        $this->dbforge->add_column('tbl_skills', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_skills', 'active');
    }
}
//