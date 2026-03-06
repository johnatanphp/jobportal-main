<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_entries_202309051740 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_employer_id' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
            'updated_employer_id' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_column('tbl_seeker_entries', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_entries', 'updated_at');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'created_employer_id');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'updated_employer_id');
    }
}
