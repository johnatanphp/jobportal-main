<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_exam_request_overall_emails_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '180',
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_exam_request_overall_emails');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_exam_request_overall_emails');
    }
}
