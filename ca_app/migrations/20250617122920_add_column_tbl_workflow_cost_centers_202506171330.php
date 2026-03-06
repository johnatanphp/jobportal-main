<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_workflow_cost_centers_202506171330 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => TRUE,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => TRUE,
            ],
        ];

        $this->dbforge->add_column('tbl_workflow_cost_centers', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_workflow_cost_centers', 'created_at');
        $this->dbforge->drop_column('tbl_workflow_cost_centers', 'updated_at');
    }
}
