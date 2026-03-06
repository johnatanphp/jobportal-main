<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_entries_table_202308161501 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'city' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'gender'
            ],
            'recruitment_channel' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'city'
            ],
            'referred_by' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'recruitment_channel'
            ],
            'recruitment_stage' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'referred_by'
            ],
            'recruitment_seeker_status' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'recruitment_stage'
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '180',
                'default' => '',
                'after' => 'recruitment_seeker_status'
            ],
            'company_account' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '180',
                'default' => '',
                'after' => 'job_title'
            ],
            'management' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'company_account'
            ],
            'employer_selector' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '120',
                'default' => '',
                'after' => 'management'
            ]
        ];

        $this->db->query('ALTER TABLE tbl_seeker_entries DROP PRIMARY KEY');
        $this->db->query('CREATE UNIQUE INDEX unique_index_email ON tbl_seeker_entries(email)');

        $this->dbforge->add_field("id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        $this->dbforge->add_column('tbl_seeker_entries', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_entries', 'id');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'city');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'recruitment_channel');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'referred_by');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'recruitment_stage');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'recruitment_seeker_status');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'job_title');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'company_account');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'management');
        $this->dbforge->drop_colum('tbl_seeker_entries', 'employer_selector');
    }
}
