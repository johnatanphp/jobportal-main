<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_short_list_tokens_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'unique' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_short_list_tokens');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_short_list_tokens');
    }
}
