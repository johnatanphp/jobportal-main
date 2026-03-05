<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contracts_synchronization_logs_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'job_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'sync_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'url' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'parameters' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'response' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'success' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->add_key('success');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (sync_id) REFERENCES tbl_recruitment_contracts_synchronizations(id)');

        $this->dbforge->create_table('tbl_recruitment_contracts_synchronization_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contracts_synchronization_logs');
    }
}
