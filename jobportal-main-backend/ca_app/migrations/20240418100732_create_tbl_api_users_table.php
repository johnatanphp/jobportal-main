<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_api_users_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'access_token_prefix' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'unique' => true
            ],
            'access_token' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
                'null' => true
            ],
            'access_token_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'access_token_time_life' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 6
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_api_users');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_api_users');
    }
}
