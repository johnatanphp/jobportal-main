<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_candidates_202309271319 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'update_date' => [
                'type' => 'DATE',
                'null' => true
            ]
        ];


        $this->dbforge->add_column('tbl_recruitment_candidates', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_candidates', 'update_date');
    }
}
