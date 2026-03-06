<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_layout_alert_emails_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
                'null' => false
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ]
        ]);
        
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->create_table('tbl_job_layout_alert_emails');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_layout_alert_emails');
    }
}
