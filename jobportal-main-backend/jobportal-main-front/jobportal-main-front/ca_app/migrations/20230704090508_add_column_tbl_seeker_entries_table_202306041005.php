<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_entries_table_202306041005 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'seeker_id' => [
                'type' => 'INT',
                'null' => true,
            ]
        ];
        $this->dbforge->add_column('tbl_seeker_entries', $fields); 
        $this->dbforge->add_key('seeker_id'); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_entries', 'seeker_id');
    }
}
