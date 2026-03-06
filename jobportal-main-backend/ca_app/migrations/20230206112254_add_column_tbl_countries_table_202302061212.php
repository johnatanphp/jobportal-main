<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_countries_table_202302061212 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'currency_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => ''
            ]
        ];

        $this->dbforge->add_column('tbl_countries', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_countries', 'currency_code');
    }
}
