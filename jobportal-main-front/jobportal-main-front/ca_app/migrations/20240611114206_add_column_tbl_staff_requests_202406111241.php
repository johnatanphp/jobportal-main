<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202406111241 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'end_date_work' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'start_date_work',
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'end_date_work');
    }
}
