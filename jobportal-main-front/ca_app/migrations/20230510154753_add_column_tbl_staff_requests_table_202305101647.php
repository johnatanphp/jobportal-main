<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_table_202305101647 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'observation' => [
                'type' => 'TEXT',
                'null' => false
            ]
        ];
        $this->dbforge->add_column('tbl_staff_requests', $fields);  
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'observation');
    }
}
