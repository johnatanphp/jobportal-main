<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_contracts_202310021537 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'synchronized' => [
                'type' => 'INT',
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_contracts', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_contracts', 'synchronized');
    }
}
