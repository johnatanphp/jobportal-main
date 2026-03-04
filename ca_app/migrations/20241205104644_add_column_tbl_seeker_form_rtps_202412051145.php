<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_202412051145 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'bank_interbank_account_number' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => '',
                'after' => 'bank_account_type'
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);
    }

    public function down(){}
}
