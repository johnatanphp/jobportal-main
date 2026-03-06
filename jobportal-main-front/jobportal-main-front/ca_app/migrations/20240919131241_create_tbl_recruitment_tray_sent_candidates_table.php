<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_sent_candidates_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'tray_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'sent_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (tray_id) REFERENCES tbl_recruitment_tray_candidates(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (sent_id) REFERENCES tbl_recruitment_tray_sent(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_recruitment_tray_sent_candidates');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_sent_candidates');
    }
}
