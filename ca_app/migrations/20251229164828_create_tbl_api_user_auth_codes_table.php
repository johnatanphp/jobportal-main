<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_api_user_auth_codes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false
            ],
            'user_type' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
                'null' => false
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'redirect_uri' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'scopes' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'code_challenge' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'is_used' => [
                'type' => 'tinyint',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 0
            ],
            'access_token' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('code', TRUE);
        $this->dbforge->add_key('is_used');
        $this->dbforge->add_key('user_id');
        $this->dbforge->create_table('tbl_api_user_auth_codes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_api_user_auth_codes');
    }
}
