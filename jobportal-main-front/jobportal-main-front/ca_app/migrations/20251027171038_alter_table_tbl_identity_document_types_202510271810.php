<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_identity_document_types_202510271810 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'order' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 1,
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 1,
            ]
        ];

        $this->dbforge->add_column('tbl_identity_document_types', $fields);
    }

    public function down(){}
}
