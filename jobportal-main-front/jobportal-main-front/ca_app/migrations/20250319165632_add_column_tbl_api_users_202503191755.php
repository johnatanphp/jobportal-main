<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_api_users_202503191755 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'owner_name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => false,
                'default' => '',
                'after' => 'id'
            ]
        ];

        $this->dbforge->add_column('tbl_api_users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_api_users', 'owner_name');
    }
}
