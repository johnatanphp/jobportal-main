<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_api_users_202503191705 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'allowed_cors_origins' => [
                'type' => 'TEXT',
                'null' => false
            ]
        ];

        $this->dbforge->add_column('tbl_api_users', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_api_users', 'allowed_cors_origins');
    }
}
