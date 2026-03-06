<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_staff_requests_202511121620 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'has_penalty' => [
                'type' => 'tinyint',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields);
    }

    public function down(){}
}
