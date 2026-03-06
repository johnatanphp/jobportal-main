<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_workflow_cost_centers_202511121210 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'has_penalty' => [
                'type' => 'tinyint',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0,
                'after' =>'active'
            ]
        ];

        $this->dbforge->add_column('tbl_workflow_cost_centers', $fields);
    }

    public function down(){}
}
