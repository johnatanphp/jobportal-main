<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_candidates_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false 
            ],
            'seeker_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'job_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (status_id) REFERENCES tbl_recruitment_tray_status(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->add_key('client_code');
        $this->dbforge->create_table('tbl_recruitment_tray_candidates');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_candidates');
    }
}
