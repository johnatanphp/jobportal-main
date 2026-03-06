<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_entries_202307191913 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'error_log' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ]
        ];
        $this->dbforge->add_column('tbl_seeker_entries', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_entries', 'error_log');
    }
}
