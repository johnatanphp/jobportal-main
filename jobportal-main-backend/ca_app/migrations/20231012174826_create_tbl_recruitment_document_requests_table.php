<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_document_requests_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => TRUE
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false,
                'unique' => true
            ],
            'token_created_at' => [
                'type' => 'DATETIME',
               'null' => false
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->create_table('tbl_recruitment_document_requests');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_document_requests');
    }
}
