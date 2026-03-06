<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_countries_202512051208 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'hrmgo_code' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
                'after' => 'flag_icon'
            ],
            'hrmgo_nationality_code' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
                'after' => 'hrmgo_code'
            ]
        ];

        $this->dbforge->add_column('tbl_countries', $fields);
    }

    public function down(){}
}
