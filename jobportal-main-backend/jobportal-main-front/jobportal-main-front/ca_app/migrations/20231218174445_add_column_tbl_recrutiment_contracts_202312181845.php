<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recrutiment_contracts_202312181845 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'employee_type_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'employee_type_id');
    }
}
