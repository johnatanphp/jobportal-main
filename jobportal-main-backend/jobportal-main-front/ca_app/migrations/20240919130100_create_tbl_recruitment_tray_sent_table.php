<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_tray_sent_table extends CI_Migration
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
            'company_id' => [
                'type' => 'INT',
                'null' => false 
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'sent_by' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'comments' => [
                'type' => 'TEXT',
                'null' => false,
                'default' => ''
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (sent_by) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('client_code');
        $this->dbforge->create_table('tbl_recruitment_tray_sent');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_tray_sent');
    }
}
