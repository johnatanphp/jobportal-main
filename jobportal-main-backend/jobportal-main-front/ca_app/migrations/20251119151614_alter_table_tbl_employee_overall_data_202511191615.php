<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_employee_overall_data_202511191615 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'CHAR',
                'constraint' => '4',
                'null' => false,
                'after' => 'document_number'
            ]
        ];

        $this->dbforge->add_column('tbl_employee_overall_data', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_employee_overall_data', 'status');
    }
}
