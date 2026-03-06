<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202312081600 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'due_date' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_screening', $fields); 
        $this->db->query("CREATE INDEX due_date_index ON tbl_screening (due_date)"); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_screening', 'due_date');
    }
}
