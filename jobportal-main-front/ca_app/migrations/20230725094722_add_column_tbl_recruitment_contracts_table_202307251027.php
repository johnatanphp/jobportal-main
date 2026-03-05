<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_table_202307251027 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'hired' => [
                'type' => 'INT',
                'null' => false,
                'after' => 'contract_end_date'
            ]
        ];
        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'hired');
    }
}
