<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_business_units_table_202302171151 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'acronym_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_business_units', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_business_units', 'acronym_code');
    }
}
