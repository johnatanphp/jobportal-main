<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_workflow_consultants_202407181437 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'ruc' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
                'null' => true,
                'after' => 'name',
            ],
        ];

        $this->dbforge->add_column('tbl_workflow_consultants', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_workflow_consultants', 'ruc');
    }
}
