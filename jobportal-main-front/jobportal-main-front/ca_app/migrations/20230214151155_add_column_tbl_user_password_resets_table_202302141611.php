<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_user_password_resets_table_202302141611 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'verification_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_user_password_resets', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_user_password_resets', 'verification_code');
    }
}
