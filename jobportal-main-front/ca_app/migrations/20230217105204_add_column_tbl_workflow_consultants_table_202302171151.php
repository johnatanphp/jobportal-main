<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_workflow_consultants_table_202302171151 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'type_service' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_workflow_consultants', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_workflow_consultants', 'type_service');
    }
}
