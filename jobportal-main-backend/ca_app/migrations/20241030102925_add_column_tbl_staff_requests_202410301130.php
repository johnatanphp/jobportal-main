<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202410301130 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'update_date' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'creation_date'
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_staff_requests', 'update_date');
    }
}
