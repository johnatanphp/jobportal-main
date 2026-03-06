<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_seeker_entries_job_offers_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'seeker_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'job_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'offer_sent' => [
                'type' => 'TINYINT',
                'default' => 0,
                'null' => false
            ],
            'offer_sent_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'offer_sent_error' => [
                'type' => 'TINYINT',
                'default' => 0,
                'null' => false
            ],
            'offer_sent_log' => [
                'type' => 'LONGTEXT',
                'null' => true
            ],
            'offer_sent_by' => [
                'type' => 'INT',
                'null' => false
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('offer_sent');
        $this->dbforge->add_key('seeker_id');
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('offer_sent_error');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (offer_sent_by) REFERENCES tbl_employers(ID)');

        $this->dbforge->create_table('tbl_seeker_entries_job_offers');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_seeker_entries_job_offers');
    }
}
