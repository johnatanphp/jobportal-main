<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_logs_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'session_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'server_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'get_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'post_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('created_at');
        $this->dbforge->create_table('tbl_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_logs');
    }
}
