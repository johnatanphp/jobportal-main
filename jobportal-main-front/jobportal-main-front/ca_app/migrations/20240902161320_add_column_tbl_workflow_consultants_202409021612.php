<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_workflow_consultants_202409021612 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'ind_own_company' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false
            ],
        ];

        $this->dbforge->add_column('tbl_workflow_consultants', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_workflow_consultants', 'ind_own_company');
    }
}
