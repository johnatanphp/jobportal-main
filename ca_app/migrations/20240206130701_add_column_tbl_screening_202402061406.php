<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202402061406 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'after' => 'job_id'
            ]
        ];

        $this->dbforge->add_column('tbl_screening', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'job_title');
    }
}
