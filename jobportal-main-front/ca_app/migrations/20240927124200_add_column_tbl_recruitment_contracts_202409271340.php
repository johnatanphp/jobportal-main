<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_202409271340 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'synchronization_error' => [
                'type' => 'TEXT',
                'null' => false,
                'after' => 'synchronized'
            ],
            'synchronization_attempts' => [
                'type' => 'INT',
                'null' => false,
                'after' => 'synchronization_error',
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'synchronization_error');
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'synchronization_attempts');
    }
}
