<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202405161330 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'eecc_form_id' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'after' => 'eecc_description',
                'null' => false,
            ]
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'eecc_form_id');
    }
}
