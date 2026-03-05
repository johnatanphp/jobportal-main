<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_add_column_tbl_recruitment_contracts_202312181320 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'employee_category_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'employee_category_id');
    }
}
