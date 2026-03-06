<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_api_user_tokens_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'app_user_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
            'user_type' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
            ],
            'access_token' => [
                'type' => 'VARCHAR',
                'constraint' => '36',
                'unique' => true
            ],
            'expires_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'revoked' => [
                'type' => 'TINYINT',
                'default' => 0
            ],
            'revoked_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'update_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('app_user_id');
        $this->dbforge->add_key('revoked');

        $this->dbforge->create_table('tbl_api_user_tokens');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_api_user_tokens');
    }
}
