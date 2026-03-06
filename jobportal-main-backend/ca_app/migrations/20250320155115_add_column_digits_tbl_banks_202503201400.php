<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_digits_tbl_banks_202503201400 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'account_digits' => [
                'type'       => 'TINYINT',
                'unsigned'   => TRUE,
                'null'       => FALSE,
                'default'    => 0,
                'comment'    => 'Número de dígitos para la cuenta. 0 = sin validación.'
            ],
            'cci_digits' => [
                'type'       => 'TINYINT',
                'unsigned'   => TRUE,
                'null'       => FALSE,
                'default'    => 20,
                'comment'    => 'Número de dígitos para la cuenta. 0 = sin validación.'
            ]
        ];

        $this->dbforge->add_column('tbl_banks', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_banks', 'account_digits');
    }
}
