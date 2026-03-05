<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_staff_requests_202310271535 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'contract_type_model_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'contract_type_model_name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => true
            ],
        ];

        $this->dbforge->add_column('tbl_staff_requests', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_staff_requests', 'contract_type_model_code');
        $this->dbforge->drop_colum('tbl_staff_requests', 'contract_type_model_name');
    }
}
