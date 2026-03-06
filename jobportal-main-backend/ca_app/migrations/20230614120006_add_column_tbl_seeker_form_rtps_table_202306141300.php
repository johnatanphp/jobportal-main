<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_form_rtps_table_202306141300 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'degree_obtained_institution' => [
                'type' => 'TEXT',
                'null' => false,
                'default' => '',
                'after' => 'degree_obtained'
            ],
            'degree_obtained_year' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'degree_obtained_institution'
            ],
            'payment_cts_bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'default' => ''
            ],
            'payment_cts_currency' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
                'default' => ''
            ]
        ];
        $this->dbforge->add_column('tbl_seeker_form_rtps', $fields);  
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'degree_obtained_institution');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'degree_obtained_year');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'payment_cts_bank_name');
        $this->dbforge->drop_colum('tbl_seeker_form_rtps', 'payment_cts_currency');
    }
}
