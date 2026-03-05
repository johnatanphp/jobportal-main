<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_202312191230 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'contract_type_model_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'contract_type_model_name' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
                'null' => true
            ],
        ];
     
        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'contract_type_model_code');
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'contract_type_model_name');
    }
}
