<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_candidate_sources_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'process_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'seeker_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'source_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'social_network_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'referred_by_identification_document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'referred_by_first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => false
            ],
            'referred_by_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                 'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (process_id) REFERENCES tbl_recruitment_process(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (seeker_id) REFERENCES tbl_job_seekers(ID) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (source_id) REFERENCES tbl_recruitment_sources(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (social_network_id) REFERENCES tbl_social_networks(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        
        $this->dbforge->create_table('tbl_recruitment_candidate_sources');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_candidate_sources');
    }
}
