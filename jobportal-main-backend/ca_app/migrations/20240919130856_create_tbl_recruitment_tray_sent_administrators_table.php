<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_sent_administrators_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'sent_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (sent_id) REFERENCES tbl_recruitment_tray_sent(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('tbl_recruitment_tray_sent_administrators');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_sent_administrators');
    }
}
