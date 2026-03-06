<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_countries_202510301320 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'has_operation_overall' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
            ]
        ];

        $this->dbforge->add_column('tbl_countries', $fields);
    }

    public function down(){}
}
